<?php

declare(strict_types=1);

namespace App\Services\ReportService;

use App\DataAdapters\BookingDataAdapter\BookingDataAdaptor;
use App\DataAdapters\StatementServiceDataAdapter\StatementDataAdapter;
use App\Enums\ReportPeriod;
use app\Enums\StatementStatus;
use App\Exceptions\MailSendException;
use App\Models\Booking;
use App\Models\Statement;
use App\Services\MailService\MailService;
use Illuminate\Support\Facades\DB;
use App\DataAdapters\UserServiceDataAdapter\UserDataAdapter;

readonly class ReportService
{
    public function __construct(
        private StatementDataAdapter $statementDataAdapter,
        private MailService          $mailService,
        private BookingDataAdaptor   $bookingDataAdapter,
        private UserDataAdapter      $userDataAdapter,
    ) {}

    /**
     * @throws MailSendException
     */
    public function statementsSummary(ReportPeriod $period): void
    {
        $query = Statement::query();

        if ($startDate = $period->getStartDate()) {
            $query->where('created_at', '>=', $startDate);
        }

        $result = $query->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();

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
        $groupBooking = Booking::query()
            ->join('resources', 'bookings.resource_id', '=', 'resources.id')
            ->selectRaw('resource_id, resources.name as resource_name, count(*) as count')
            ->groupBy('resource_id', 'resources.name')
            ->get();

        $data = $this->bookingDataAdapter->createBookingsByResourceDTOFromCollection($groupBooking);

        $this->mailService->sendBookingsByResourceReport($data);
    }

    /**
     * @throws MailSendException
     */
    public function userActivity(): void
    {
        $userActivity = DB::table('users')
            ->leftJoin('statements', 'statements.user_id', '=', 'users.id')
            ->leftJoin('bookings', 'bookings.user_id', '=', 'users.id')
            ->select(
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email',
                DB::raw('COUNT(DISTINCT statements.id) as statements_count'),
                DB::raw('COUNT(DISTINCT bookings.id) as bookings_count')
            )
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByRaw('COUNT(DISTINCT statements.id) + COUNT(DISTINCT bookings.id) DESC')
            ->get();

        $data = $this->userDataAdapter->createUserActivityDTOByObject($userActivity);

        $this->mailService->sendUserActivityReport($data);
    }

    /**
     * @throws MailSendException
     */
    public function statementsTrend(ReportPeriod $period): void
    {
        $query = Statement::query();

        if ($startDate = $period->getStartDate()) {
            $query->where('created_at', '>=', $startDate);
        }

        $result = $query
            ->selectRaw("TO_CHAR(created_at, 'YYYY-MM') as period, count(*) as count")
            ->groupByRaw("TO_CHAR(created_at, 'YYYY-MM')")
            ->orderByRaw('1')
            ->get();

        $data = $this->statementDataAdapter->createStatementsTrendDTOsFromCollection($result);

        $this->mailService->sendStatementsTrendReport($data);
    }
}
