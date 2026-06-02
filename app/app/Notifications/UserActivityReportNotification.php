<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Mail\Mailable;

class UserActivityReportNotification extends Mailable
{
    public function __construct(
        public string $attachmentPath,
    ) {}

    public function build(): self
    {
        return $this->subject('User activity report')
            ->view('emails.user_activity_report')
            ->attach($this->attachmentPath, [
                'as' => basename($this->attachmentPath),
                'mime' => 'text/csv',
            ]);
    }
}
