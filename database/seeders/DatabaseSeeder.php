<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $role = Role::create(['name' => 'Super Admin']);

        $user = User::create([
            'name' => 'Rafi',
            'email' => 'rafi@gmail.com',
            'password' => bcrypt('password'),
            'department' => 'Umum'
        ]);

        $user->assignRole($role);

        Role::create(['name' => 'Customer']);
    }
}
