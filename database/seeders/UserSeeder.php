<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::factory()->create([
            'username' => 'admin',
            'password' => 'admin',
            'role' => 'Super Admin',
            'approved' => true,
        ]);

        $admin->confirmation->approved = true;
        $admin->confirmation->save();
    }
}
