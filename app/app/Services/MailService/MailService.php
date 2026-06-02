<?php

declare(strict_types=1);

namespace App\Services\MailService;

use App\Data\StatementsSummaryReportDTO\StatementsSummaryReportDTO;
use App\Exceptions\MailSendException;
use App\Notifications\BookingByResourceReportNotification;
use App\Notifications\StatementsSummaryReportNotification;
use App\Notifications\StatementsTrendReportNotification;
use App\Notifications\UserActivityReportNotification;
use App\Reports\Csv\BookingsByResourceCsvReport;
use App\Reports\Csv\CsvReportWriter;
use App\Reports\Csv\StatementsSummaryCsvReport;
use App\Reports\Csv\StatementsTrendCsvReport;
use App\Reports\Csv\UserActivityCsvReport;
use App\Repositories\UserRepository\UserRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Throwable;

readonly class MailService
{
    public function __construct(
        private UserRepository  $userRepository,
        private CsvReportWriter $csvReportWriter,
    ) {}

    /**
     * @throws MailSendException
     */
    public function sendStatementsSummaryReport(StatementsSummaryReportDTO $statementsSummaryReportDTO): void
    {
        $report = new StatementsSummaryCsvReport($statementsSummaryReportDTO);
        $path = $this->csvReportWriter->write($report, storage_path('app/cache'));
        $admin = $this->userRepository->getFirstAdmin();

        if (!$admin) {
            unlink($path);
            throw new MailSendException('Admin not found');
        }

        try {
            Mail::to($admin->email)->send(new StatementsSummaryReportNotification($path));
        } catch (Throwable $e) {
            throw new MailSendException('Could not send the statement report', $e->getCode(), $e);
        } finally {
            unlink($path);
        }
    }

    /**
     * @throws MailSendException
     */
    public function sendBookingsByResourceReport(Collection $bookings): void
    {
        $report = new BookingsByResourceCsvReport($bookings);
        $path = $this->csvReportWriter->write($report, storage_path('app/cache'));
        $admin = $this->userRepository->getFirstAdmin();

        if (!$admin) {
            unlink($path);
            throw new MailSendException('Admin not found');
        }

        try {
            Mail::to($admin->email)->send(new BookingByResourceReportNotification($path));
        } catch (Throwable $e) {
            throw new MailSendException('Could not send the bookings by resource report', $e->getCode(), $e);
        } finally {
            unlink($path);
        }
    }

    /**
     * @throws MailSendException
     */
    public function sendUserActivityReport(Collection $userActivity): void
    {
        $report = new UserActivityCsvReport($userActivity);
        $path = $this->csvReportWriter->write($report, storage_path('app/cache'));
        $admin = $this->userRepository->getFirstAdmin();

        if (!$admin) {
            unlink($path);
            throw new MailSendException('Admin not found');
        }

        try {
            Mail::to($admin->email)->send(new UserActivityReportNotification($path));
        } catch (Throwable $e) {
            throw new MailSendException('Could not send the user activity report', $e->getCode(), $e);
        } finally {
            unlink($path);
        }
    }

    /**
     * @throws MailSendException
     */
    public function sendStatementsTrendReport(Collection $statementsTrend): void
    {
        $report = new StatementsTrendCsvReport($statementsTrend);
        $path = $this->csvReportWriter->write($report, storage_path('app/cache'));
        $admin = $this->userRepository->getFirstAdmin();

        if (!$admin) {
            unlink($path);
            throw new MailSendException('Admin not found');
        }

        try {
            Mail::to($admin->email)->send(new StatementsTrendReportNotification($path));
        } catch (Throwable $e) {
            throw new MailSendException('Could not send the statements trend report', $e->getCode(), $e);
        } finally {
            unlink($path);
        }
    }
}
