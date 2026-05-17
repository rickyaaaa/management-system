<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // ── Define all permissions ──────────────────────────────────────────
        $permissions = [
            // User management
            ['name' => 'create-user',        'group' => 'user',       'display_name' => 'Create User'],
            ['name' => 'edit-user',          'group' => 'user',       'display_name' => 'Edit User'],
            ['name' => 'delete-user',        'group' => 'user',       'display_name' => 'Delete User'],
            ['name' => 'view-users',         'group' => 'user',       'display_name' => 'View Users'],

            // Task management
            ['name' => 'create-task',        'group' => 'task',       'display_name' => 'Create Task'],
            ['name' => 'edit-task',          'group' => 'task',       'display_name' => 'Edit Task'],
            ['name' => 'delete-task',        'group' => 'task',       'display_name' => 'Delete Task'],
            ['name' => 'view-tasks',         'group' => 'task',       'display_name' => 'View All Tasks'],
            ['name' => 'view-assigned-tasks','group' => 'task',       'display_name' => 'View Assigned Tasks'],
            ['name' => 'view-own-tasks',     'group' => 'task',       'display_name' => 'View Own Tasks'],
            ['name' => 'assign-task',        'group' => 'task',       'display_name' => 'Assign Task'],
            ['name' => 'submit-task',        'group' => 'task',       'display_name' => 'Submit Task'],

            // Role management
            ['name' => 'create-role',        'group' => 'role',       'display_name' => 'Create Role'],
            ['name' => 'edit-role',          'group' => 'role',       'display_name' => 'Edit Role'],
            ['name' => 'delete-role',        'group' => 'role',       'display_name' => 'Delete Role'],
            ['name' => 'view-roles',         'group' => 'role',       'display_name' => 'View Roles'],
            ['name' => 'manage-permissions', 'group' => 'role',       'display_name' => 'Manage Permissions'],

            // Review / submission
            ['name' => 'view-submissions',   'group' => 'review',     'display_name' => 'View Submissions'],
            ['name' => 'review-task',        'group' => 'review',     'display_name' => 'Review Task'],
            ['name' => 'approve-task',       'group' => 'review',     'display_name' => 'Approve Task'],
            ['name' => 'reject-task',        'group' => 'review',     'display_name' => 'Reject Task'],

            // Dashboard / performance
            ['name' => 'view-dashboard',     'group' => 'general',    'display_name' => 'View Dashboard'],
            ['name' => 'view-performance',   'group' => 'general',    'display_name' => 'View Performance'],
            ['name' => 'view-own-performance','group' => 'general',   'display_name' => 'View Own Performance'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm['name']], $perm);
        }

        // ── Define roles with their permission sets ─────────────────────────
        $roles = [
            [
                'name'         => 'admin',
                'display_name' => 'Administrator',
                'description'  => 'Full access to all features',
                'permissions'  => [
                    'create-user','edit-user','delete-user','view-users',
                    'create-task','edit-task','delete-task','view-tasks','assign-task',
                    'create-role','edit-role','delete-role','view-roles','manage-permissions',
                    'view-dashboard','view-performance',
                ],
            ],
            [
                'name'         => 'editor_3d',
                'display_name' => 'Editor 3D',
                'description'  => 'Production artist – 3D modeling and animation',
                'permissions'  => [
                    'view-assigned-tasks','submit-task','view-own-tasks','view-own-performance',
                ],
            ],
            [
                'name'         => 'editor_animasi',
                'display_name' => 'Editor Animasi',
                'description'  => 'Production artist – animation specialist',
                'permissions'  => [
                    'view-assigned-tasks','submit-task','view-own-tasks','view-own-performance',
                ],
            ],
            [
                'name'         => 'reviewer',
                'display_name' => 'Reviewer',
                'description'  => 'Quality control – reviews and approves submissions',
                'permissions'  => [
                    'view-submissions','review-task','approve-task','reject-task',
                ],
            ],
        ];

        foreach ($roles as $roleData) {
            $permNames = $roleData['permissions'];
            unset($roleData['permissions']);

            $role = Role::firstOrCreate(['name' => $roleData['name']], $roleData);

            $permIds = Permission::whereIn('name', $permNames)->pluck('id');
            $role->permissions()->sync($permIds);
        }

        $this->command->info('Roles & permissions seeded successfully.');
    }
}
