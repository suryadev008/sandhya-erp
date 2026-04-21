<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = [
            'companies'     => ['view', 'create', 'edit', 'delete'],
            'contacts'      => ['view', 'create', 'edit', 'delete'],
            'employees'     => ['view', 'create', 'edit', 'delete'],
            'machines'      => ['view', 'create', 'edit', 'delete'],
            'machine-types' => ['view', 'create', 'edit', 'delete'],
            'operations'    => ['view', 'create', 'edit', 'delete'],
            'parts'         => ['view', 'create', 'edit', 'delete'],
            'payrolls'      => ['view', 'create', 'edit', 'delete'],
            'productions'   => ['view', 'create', 'edit', 'delete'],
            'salaries'      => ['view', 'create', 'edit', 'delete'],
            'attendance'    => ['view', 'create', 'edit', 'delete'],
            'my-company'    => ['view', 'edit'],
            'users'         => ['view', 'create', 'edit', 'delete'],
            'roles'         => ['view', 'create', 'edit', 'delete'],
        ];

        $allPermissions = [];

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                $name = "{$action} {$module}";
                Permission::firstOrCreate(['name' => $name]);
                $allPermissions[] = $name;
            }
        }

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($allPermissions);

        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->syncPermissions(
            collect($allPermissions)
                ->reject(fn ($p) => str_contains($p, 'users') || str_contains($p, 'roles'))
                ->values()
                ->all()
        );

        $operator = Role::firstOrCreate(['name' => 'operator']);
        $operator->syncPermissions([
            'view productions', 'create productions',
            'view attendance', 'create attendance',
            'view machines', 'view parts', 'view operations',
        ]);

        $viewer = Role::firstOrCreate(['name' => 'viewer']);
        $viewer->syncPermissions(
            collect($allPermissions)->filter(fn ($p) => str_starts_with($p, 'view'))->all()
        );
    }
}
