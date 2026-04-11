<?php

declare(strict_types=1);

namespace App\Services\StatementService\Strategies;

use App\Enums\StatusTransitionType;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use InvalidArgumentException;
use App\Services\StatementService\Strategies\Contracts\StatusTransitionStrategyInterface;

readonly class StatusTransitionStrategyResolver
{
    public const string BINDING_PREFIX = 'status_transition.';

    public function __construct(
        private Container $container,
    ) {}

    /**
     * @throws BindingResolutionException
     */
    public function resolve(StatusTransitionType $type): StatusTransitionStrategyInterface
    {
        $key = self::BINDING_PREFIX . $type->value;

        if (!$this->container->bound($key)) {
            throw new InvalidArgumentException("No strategy registered for: {$type->value}");
        }

        return $this->container->make($key);
    }
}
