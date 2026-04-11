<?php

declare(strict_types=1);

namespace App\Services\MailService;

use App\Data\BookingDTO\BookingsByResourceDTO;
use App\Data\StatementDTO\StatementsTrendDTO;
use App\Data\StatementsSummaryReportDTO\StatementsSummaryReportDTO;
use App\Data\UserDTO\UserActivityDTO;
use App\Exceptions\MailSendException;
use App\Notifications\BookingByResourceReportNotification;
use App\Notifications\StatementsSummaryReportNotification;
use App\Notifications\StatementsTrendReportNotification;
use App\Notifications\UserActivityReportNotification;
use App\Repositories\UserRepository\UserRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Throwable;

readonly class MailService
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    /**
     * @throws MailSendException
     */
    public function sendStatementsSummaryReport(StatementsSummaryReportDTO $statementsSummaryReportDTO): void
    {
        $dir = storage_path('app/cache');

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $path = $dir . '/statement_summary_report_' . time() . '.csv';
        $report = fopen($path, 'w');

        if ($report === false) {
            throw new MailSendException("Report file could not be created: {$path}");
        }

        fputcsv($report, ['Status', 'Count']);
        fputcsv($report, ['Draft', $statementsSummaryReportDTO->draft]);
        fputcsv($report, ['Submitted', $statementsSummaryReportDTO->submitted]);
        fputcsv($report, ['Approved', $statementsSummaryReportDTO->approved]);
        fputcsv($report, ['Rejected', $statementsSummaryReportDTO->rejected]);
        fputcsv($report, ['Totals', $statementsSummaryReportDTO->totals]);
        fputcsv($report, ['Period', $statementsSummaryReportDTO->period]);
        fclose($report);

        $admin = $this->userRepository->getFirstAdmin();

        if (!$admin) {
            unlink($path);
            throw new MailSendException('Admin not found');
        }

        try {
            Mail::to($admin->email)->send(new StatementsSummaryReportNotification($path));
        } catch (Throwable $e) {
            throw new MailSendException(
                'Could not send the statement report',
                (int)$e->getCode(),
                $e,
            );
        } finally {
            unlink($path);
        }
    }

    /**
     * @throws MailSendException
     */
    public function sendBookingsByResourceReport(Collection $bookings): void
    {
        $dir = storage_path('app/cache');

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $path = $dir . '/booking_by_resource_report_' . time() . '.csv';
        $report = fopen($path, 'w');

        if ($report === false) {
            throw new MailSendException("Report file could not be created: {$path}");
        }

        fputcsv($report, ['Resource', 'Bookings count']);
        $bookings->each(fn(BookingsByResourceDTO $dto) => fputcsv($report, [$dto->resourceName, $dto->count]));
        fclose($report);
        $admin = $this->userRepository->getFirstAdmin();

        if (!$admin) {
            unlink($path);
            throw new MailSendException('Admin not found');
        }

        try {
            Mail::to($admin->email)->send(new BookingByResourceReportNotification($path));
        } catch (Throwable $e) {
            throw new MailSendException(
                'Could not send the bookings by resource report',
                (int)$e->getCode(),
                $e,
            );
        } finally {
            unlink($path);
        }
    }

    /**
     * @throws MailSendException
     */
    public function sendUserActivityReport(Collection $userActivity): void
    {
        $dir = storage_path('app/cache');

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $path = $dir . '/user_activity_report_' . time() . '.csv';
        $report = fopen($path, 'w');

        if ($report === false) {
            throw new MailSendException("Report file could not be created: {$path}");
        }

        fputcsv($report, ['User ID', 'User name', 'User email', 'Statements count', 'Bookings count']);

        $userActivity->each(fn(UserActivityDTO $dto) => fputcsv($report, [
            $dto->userId,
            $dto->userName,
            $dto->userEmail,
            $dto->statementsCount,
            $dto->bookingsCount,
        ]));

        fclose($report);
        $admin = $this->userRepository->getFirstAdmin();

        if (!$admin) {
            unlink($path);
            throw new MailSendException('Admin not found');
        }

        try {
            Mail::to($admin->email)->send(new UserActivityReportNotification($path));
        } catch (Throwable $e) {
            throw new MailSendException(
                'Could not send the user activity report',
                (int)$e->getCode(),
                $e,
            );
        } finally {
            unlink($path);
        }
    }

    /**
     * @throws MailSendException
     */
    public function sendStatementsTrendReport(Collection $statementsTrend): void
    {
        $dir = storage_path('app/cache');

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $path = $dir . '/statements_trend_report_' . time() . '.csv';
        $report = fopen($path, 'w');

        if ($report === false) {
            throw new MailSendException("Report file could not be created: {$path}");
        }

        fputcsv($report, ['Period', 'Statements count']);
        $statementsTrend->each(fn (StatementsTrendDTO $dto) => fputcsv($report, [$dto->period, $dto->count]));
        fclose($report);

        $admin = $this->userRepository->getFirstAdmin();

        if (!$admin) {
            unlink($path);
            throw new MailSendException('Admin not found');
        }

        try {
            Mail::to($admin->email)->send(new StatementsTrendReportNotification($path));
        } catch (Throwable $e) {
            throw new MailSendException(
                'Could not send the statements trend report',
                (int)$e->getCode(),
                $e,
            );
        } finally {
            unlink($path);
        }
    }
}
