<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::where('email', 'like', '%@uniplay.com')
            ->each(function ($user) {
                if (!$user->hasRole('admin')) {
                    $user->assignRole('admin');
                }
            });
    }
}
