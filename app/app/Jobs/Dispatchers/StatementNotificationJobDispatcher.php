<?php

declare(strict_types=1);

namespace App\Jobs\Dispatchers;

use App\Data\StatementNotificationPayload\StatementNotificationPayload;
use App\Enums\StatementNotificationType;
use App\Exceptions\NotFoundException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;
use RuntimeException;

readonly class StatementNotificationJobDispatcher
{
    public function __construct(
        private Container $container,
    ) {}

    /**
     * @throws NotFoundException
     * @throws BindingResolutionException
     */
    public function dispatch(StatementNotificationType $type, StatementNotificationPayload $payload): void
    {
        $map = config('statement_notifications');

        $handlerClass = $map[$type->value];

        if (!class_exists($handlerClass)) {
            throw new RuntimeException("Handler not found for type $type->value");
        }

        $this->container->make($handlerClass)->handle($payload);
    }
}
