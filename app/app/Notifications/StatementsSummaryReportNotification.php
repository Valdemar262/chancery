<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Mail\Mailable;

class StatementsSummaryReportNotification extends Mailable
{
    public function __construct(
        public string $attachmentPath,
    ) {}

    public function build(): self
    {
        return $this->subject('Statements Summary Report')
            ->view('emails.statement_summary_report')
            ->attach($this->attachmentPath, [
                'as' => 'report.csv',
                'mime' => 'text/csv',
            ]);
    }
}
