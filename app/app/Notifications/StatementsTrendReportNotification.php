<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Mail\Mailable;

class StatementsTrendReportNotification extends Mailable
{
    public function __construct(
        public string $attachmentPath,
    ) {}

    public function build(): self
    {
        return $this->subject('Statement trend report')
            ->view('emails.statements_trend_report')
            ->attach($this->attachmentPath, [
                'as'   => 'statements_trend_report.csv',
                'mime' => 'text/csv',
            ]);
    }
}
