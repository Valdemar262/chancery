<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Mail\Mailable;

class BookingByResourceReportNotification extends Mailable
{
    public function __construct(
        public string $attachmentPath,
    ) {}

    public function build(): self
    {
        return $this->subject('Booking report')
            ->view('emails.booking_by_resource_report')
            ->attach($this->attachmentPath, [
                'as' => basename($this->attachmentPath),
                'mime' => 'text/csv',
            ]);
    }
}
