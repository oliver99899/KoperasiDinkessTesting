<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $guard = config('auth.defaults.guard', 'web');

        Role::findOrCreate('anggota', $guard);
        Role::findOrCreate('verifikator', $guard);
        Role::findOrCreate('admin', $guard);
    }
}
