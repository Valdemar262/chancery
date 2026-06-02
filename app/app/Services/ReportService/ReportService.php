<?php

declare(strict_types=1);

namespace App\Services\ReportService;

use App\DataAdapters\BookingDataAdapter\BookingDataAdaptor;
use App\DataAdapters\StatementServiceDataAdapter\StatementDataAdapter;
use App\Enums\ReportPeriod;
use app\Enums\StatementStatus;
use App\Exceptions\MailSendException;
use App\Repositories\BookingRepository\BookingRepository;
use App\Repositories\StatementRepository\StatementRepository;
use App\Repositories\UserStatementBookingRepository\UserStatementBookingRepository;
use App\Services\MailService\MailService;
use App\DataAdapters\UserServiceDataAdapter\UserDataAdapter;

readonly class ReportService
{
    public function __construct(
        private StatementDataAdapter           $statementDataAdapter,
        private MailService                    $mailService,
        private BookingDataAdaptor             $bookingDataAdapter,
        private UserDataAdapter                $userDataAdapter,
        private StatementRepository            $statementRepository,
        private BookingRepository              $bookingRepository,
        private UserStatementBookingRepository $userStatementBookingRepository,
    ) {}

    /**
     * @throws MailSendException
     */
    public function statementsSummary(ReportPeriod $period): void
    {
        $result = $this->statementRepository->getSummaryByPeriod($period);

        $counts = collect(StatementStatus::cases())
            ->mapWithKeys(fn(StatementStatus $s) => [$s->value => 0])
            ->merge($result->mapWithKeys(fn($row) => [$row->status->value => (int) $row->count]));

        $this->mailService->sendStatementsSummaryReport(
            $this->statementDataAdapter->createdStatementsSummaryReportDTOByArray(
                $counts->put('totals', $counts->sum())->put('period', $period->value)
            ),
        );
    }

    /**
     * @throws MailSendException
     */
    public function bookingsByResource(): void
    {
        $groupBooking = $this->bookingRepository->getGroupBookingByResource();

        $data = $this->bookingDataAdapter->createBookingsByResourceDTOFromCollection($groupBooking);

        $this->mailService->sendBookingsByResourceReport($data);
    }

    /**
     * @throws MailSendException
     */
    public function userActivity(): void
    {
        $userActivity = $this->userStatementBookingRepository->getUserActivityByStatementForBooking();

        $data = $this->userDataAdapter->createUserActivityDTOByObject($userActivity);

        $this->mailService->sendUserActivityReport($data);
    }

    /**
     * @throws MailSendException
     */
    public function statementsTrend(ReportPeriod $period): void
    {
        $result = $this->statementRepository->getTrendByPeriod($period);

        $data = $this->statementDataAdapter->createStatementsTrendDTOsFromCollection($result);

        $this->mailService->sendStatementsTrendReport($data);
    }
}
