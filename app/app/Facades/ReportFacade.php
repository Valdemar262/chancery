<?php

declare(strict_types=1);

namespace App\Facades;

use App\Services\ReportService\ReportService;
use Illuminate\Support\Facades\Facade;

class ReportFacade extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return ReportService::class;
    }
}
