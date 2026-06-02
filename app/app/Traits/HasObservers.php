<?php

declare(strict_types=1);

namespace App\Traits;

trait HasObservers
{
    protected static array $observers = [];

    public static function observeDomain(string $observerClass): void
    {
        static::$observers[static::class][] = $observerClass;
    }

    protected function fireEvent(string $event): void
    {
        $observers = static::$observers[static::class] ?? [];
        foreach ($observers as $observerClass) {
            $observer = app($observerClass);

            if (method_exists($observer, $event)) {
                $observer->{$event}($this);
            }
        }
    }
}
