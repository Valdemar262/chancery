<?php

declare(strict_types=1);

namespace Database\Seeders\EntityFactory;

use App\Enums\EntitySeedType;
use Database\Seeders\Contracts\EntitySeederInterface;
use InvalidArgumentException;

class EntitySeedFactory
{
    private array $map = [];

    public function create(EntitySeedType $type): EntitySeederInterface
    {
        $map = $this->getMap();
        $seederClass = $map[$type->value];

        if (!class_exists($seederClass) || $seederClass === null) {
            throw new InvalidArgumentException("No seeder registered for entity type: {$type->value}");
        }

        return app($seederClass);
    }

    private function getMap(): array
    {
        if ($this->map !== []) {
            return $this->map;
        }

        $seederClasses = config('entity_seeders');

        if (!is_array($seederClasses)) {
            return $this->map;
        }

        foreach ($seederClasses as $class) {
            if (!is_subclass_of($class, EntitySeederInterface::class)) {
                continue;
            }

            $type = $class::supports();

            $this->map[$type->value] = $class;
        }

        return $this->map;
    }
}
