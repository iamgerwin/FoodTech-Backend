<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Enums\UserType;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (UserType::cases() as $type) {
            Role::firstOrCreate(
                [
                    'id' => (string) Str::uuid(),
                    'name' => $type->key(),
                ],
                [
                    'guard_name' => 'web',
                    'label' => $type->label(),
                    'description' => $type->description(),
                ]
            );
        }
    }
}
