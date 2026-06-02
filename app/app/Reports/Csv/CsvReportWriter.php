<?php

declare(strict_types=1);

namespace App\Reports\Csv;

use App\Exceptions\MailSendException;

class CsvReportWriter
{
    /**
     * @throws MailSendException
     */
    public function write(CsvReport $report, string $directory): string
    {
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $path = $directory . '/' . $report->filename();
        $handle = fopen($path, 'w');

        if ($handle === false) {
            throw new MailSendException("Report file could not be created: {$path}");
        }

        foreach ($report as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);

        return $path;
    }
}
