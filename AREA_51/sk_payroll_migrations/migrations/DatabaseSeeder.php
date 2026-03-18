<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ── Permissions ────────────────────────────────────────────────
        $permissions = [
            // Employee
            'employees.view',
            'employees.create',
            'employees.edit',
            'employees.delete',

            // CNC Rates
            'cnc_rates.view',
            'cnc_rates.create',
            'cnc_rates.edit',

            // Master data (companies, parts, machines, operations)
            'masters.view',
            'masters.manage',    // create + edit + delete

            // Lathe Production
            'production.lathe.view',
            'production.lathe.create',
            'production.lathe.edit',
            'production.lathe.delete',

            // CNC Production
            'production.cnc.view',
            'production.cnc.create',
            'production.cnc.edit',
            'production.cnc.delete',

            // Attendance
            'attendance.view',
            'attendance.manage',

            // Payroll
            'payroll.view',
            'payroll.generate',
            'payroll.approve',
            'payroll.mark_paid',

            // Extra payments
            'extra_payments.manage',

            // Salary slip
            'salary_slip.generate',
            'salary_slip.send_whatsapp',

            // Reports
            'reports.view',

            // Users & Roles (admin only)
            'users.manage',
            'roles.manage',

            // Audit log
            'activity_logs.view',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // ── Roles & Permission assignment ──────────────────────────────

        // ADMIN — full access
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        // HR — employee + payroll + salary slips (cannot approve payroll)
        $hr = Role::firstOrCreate(['name' => 'hr', 'guard_name' => 'web']);
        $hr->syncPermissions([
            'employees.view', 'employees.create', 'employees.edit',
            'cnc_rates.view', 'cnc_rates.create', 'cnc_rates.edit',
            'masters.view',
            'production.lathe.view',
            'production.cnc.view',
            'attendance.view', 'attendance.manage',
            'payroll.view', 'payroll.generate',
            'extra_payments.manage',
            'salary_slip.generate', 'salary_slip.send_whatsapp',
            'reports.view',
        ]);

        // STAFF — production data entry only
        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $staff->syncPermissions([
            'employees.view',
            'masters.view',
            'production.lathe.view', 'production.lathe.create', 'production.lathe.edit',
            'production.cnc.view', 'production.cnc.create', 'production.cnc.edit',
            'attendance.view',
        ]);

        // OPERATOR — can only view their own salary slip (enforced in controller/policy)
        $operator = Role::firstOrCreate(['name' => 'operator', 'guard_name' => 'web']);
        $operator->syncPermissions([
            'salary_slip.generate',  // view only — policy restricts to own record
            'production.lathe.view',
            'production.cnc.view',
        ]);

        // ── Default Admin User ─────────────────────────────────────────
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@skpayroll.com'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('Admin@1234'),
            ]
        );
        $adminUser->assignRole('admin');

        $this->command->info('✅ Roles, permissions and admin user seeded successfully.');
        $this->command->info('   Login: admin@skpayroll.com  |  Password: Admin@1234');
        $this->command->info('   ⚠  Change the password immediately after first login!');
    }
}
