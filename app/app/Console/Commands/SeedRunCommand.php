<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\EntitySeedType;
use Database\Seeders\EntityFactory\EntitySeedFactory;
use Illuminate\Console\Command;

class SeedRunCommand extends Command
{
    protected $signature = 'seed:run {entity} {--count=10} {--fresh}';

    protected $description = 'Execute entity seeders by factory';

    public function __construct(
        protected EntitySeedFactory $factory,
    ) {
        parent::__construct();
    }

    public function __invoke(): int
    {
        $argument = $this->argument('entity');

        $entitySeedType = EntitySeedType::tryFrom($argument);

        if ($entitySeedType === null) {
            $this->error('Please enter the correct entity-type');
            return self::FAILURE;
        }

        $seeder = $this->factory->create($entitySeedType);
        $seeded = $seeder->run(
            (int)$this->option('count'),
            (bool)$this->option('fresh'),
        );

        $this->info("Seeded {$seeded} records for entity: {$argument}");

        return self::SUCCESS;
    }
}
