<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\ReportPeriod;
use App\Enums\ReportType;
use App\Exceptions\MailSendException;
use App\Facades\ReportFacade;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

class SendReportCommand extends Command implements PromptsForMissingInput
{
    protected $signature = 'report:send {type : change type report} {--period= : select period}';

    protected $description = 'Create and send report';

    /**
     * @throws MailSendException
     */
    public function handle(): int
    {
        $type = ReportType::tryFrom($this->argument('type'));

        if (!$type) {
            $this->error('Unknown type report. Values: ' . implode(', ', array_column(ReportType::cases(), 'value')));

            return self::FAILURE;
        }

        $periodOption = $this->option('period');

        match ($type) {
            ReportType::STATEMENTS_SUMMARY => $this->sendStatementsSummary($periodOption),
            ReportType::BOOKINGS_BY_RESOURCE => ReportFacade::bookingsByResource(),
            ReportType::USER_ACTIVITY => ReportFacade::userActivity(),
            ReportType::STATEMENTS_TREND => $this->sendStatementsTrend($periodOption),
        };

        return self::SUCCESS;
    }

    /**
     * @throws MailSendException
     */
    private function sendStatementsSummary(?string $periodOption): void
    {
        $period = $this->resolvePeriod($periodOption);
        ReportFacade::statementsSummary($period);
    }

    /**
     * @throws MailSendException
     */
    private function sendStatementsTrend(?string $periodOption): void
    {
        $period = $this->resolvePeriod($periodOption);
        ReportFacade::statementsTrend($period);
    }

    private function resolvePeriod(?string $periodOption): ReportPeriod|int
    {
        if ($periodOption !== null) {
            $period = ReportPeriod::tryFrom($periodOption);

            if (!$period) {
                $this->error('Invalid period');

                return self::FAILURE;
            }

            return $period;
        }

        $answer = $this->choice(
            'Select period',
            array_column(ReportPeriod::cases(), 'value'),
            ReportPeriod::ALL->value,
        );

        return ReportPeriod::from($answer);
    }

    public function promptForMissingArgumentsUsing(): array
    {
        return [
            'type'   => 'What type of report do you need?',
            'period' => 'For what period are you interested in the report?',
        ];
    }
}
