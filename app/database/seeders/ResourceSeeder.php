<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Resource;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        Resource::query()->truncate();

        Resource::query()->insert([
            [
                'name'        => 'One-room apartment',
                'type'        => 'Room',
                'description' => 'Room description',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Private house',
                'type'        => 'House',
                'description' => 'House description',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Private villa',
                'type'        => 'Villa',
                'description' => 'Villa description',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
