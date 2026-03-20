<?php

declare(strict_types=1);

namespace App\Services\StatementService\Strategies;

use App\Enums\StatusTransitionType;
use App\Services\StatementService\Strategies\Contracts\StatusTransitionStrategyInterface;

readonly class StatusTransitionStrategyResolver
{
    public function __construct(
        private ClientSubmitTransitionStrategy $submitStrategy,
        private AdminApproveTransitionStrategy $approveStrategy,
        private AdminRejectTransitionStrategy  $rejectStrategy,
    ) {}

    public function resolve(StatusTransitionType $type): StatusTransitionStrategyInterface
    {
        return match ($type) {
            StatusTransitionType::SUBMIT => $this->submitStrategy,
            StatusTransitionType::APPROVE => $this->approveStrategy,
            StatusTransitionType::REJECT => $this->rejectStrategy,
        };
    }
}
