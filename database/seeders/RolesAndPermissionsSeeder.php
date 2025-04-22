<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create Permissions
        Permission::create(['name' => 'create-task']);
        Permission::create(['name' => 'assign-task']);
        Permission::create(['name' => 'view-task']);
        Permission::create(['name' => 'delete-task']);
        Permission::create(['name' => 'update-task']);

        // Create Roles
        $adminRole = Role::create(['name' => 'admin']);
        $employeeRole = Role::create(['name' => 'employee']);

        // Assign Permissions to Roles
        $adminRole->givePermissionTo(['create-task', 'assign-task', 'view-task', 'delete-task']);
        $employeeRole->givePermissionTo('view-task','update-task');

        // Create Admin User
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678'),
        ]);
        $adminUser->assignRole('admin');

        // Create Employee User
        $employeeUser = User::create([
            'name' => 'Employee User',
            'email' => 'employee@employee.com',
            'password' => bcrypt('12345678'),
        ]);
        $employeeUser->assignRole('employee');
    }
}
