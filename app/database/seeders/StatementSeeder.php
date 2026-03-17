<?php

namespace Database\Seeders;

use App\Models\Statement;
use App\Models\User;
use Illuminate\Database\Seeder;

class StatementSeeder extends Seeder
{
    public function run(): void
    {
        Statement::truncate();

        User::all()->each(function ($user) {
            Statement::factory()->count(5)->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
