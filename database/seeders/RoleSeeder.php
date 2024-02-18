<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleList = [
            'category' => [
                'view',
                'add',
                'edit',
                'delete'
            ],
            'supplier' => [
                'view',
                'add',
                'edit',
                'delete'
            ],
            'product' => [
                'view',
                'add',
                'edit',
                'delete'
            ],
            'price' => [
                'view',
                'add',
                'edit',
                'delete'
            ],
            'stock' => [
                'view',
                'add',
                'edit',
                'delete'
            ],
            'logs' => [
                'view',
            ],
            'report' => [
                'view',
            ],
            'user' => [
                'view',
                'add',
                'edit',
                'delete',
            ]
        ];
        $role = new Role();
        $role->role_name = 'Administrator';
        $role->access_items = json_encode($roleList);
        $role->save();

        $roleList = [
            'category' => [
                'view',
                'add',
                'edit',
                'delete'
            ],
            'supplier' => [
                'view',
                'add',
                'edit',
                'delete'
            ],
            'product' => [
                'view',
                'add',
                'edit',
                'delete'
            ],
            'price' => [
                'view',
                'add',
                'edit',
                'delete'
            ],
            'stock' => [
                'view',
                'add',
                'edit',
                'delete'
            ],
            'report' => [
                'view',
            ],
            'logs' => [
                'view',
            ]
        ];
        $role = new Role();
        $role->role_name = 'Manager';
        $role->access_items = json_encode($roleList);
        $role->save();

        $roleList = [
            'category' => [
                'view',
                'add',
                'edit'
            ],
            'supplier' => [
                'view',
                'add',
                'edit'
            ],
            'product' => [
                'view',
                'add',
                'edit'
            ],
            'price' => [
                'view',
                'add',
                'edit'
            ],
            'stock' => [
                'view',
                'add',
                'edit'
            ],
            'report' => [
                'view',
            ]
        ];
        $role = new Role();
        $role->role_name = 'Supervisor';
        $role->access_items = json_encode($roleList);
        $role->save();

        $roleList = [
            'category' => [
                'view',
                'add'
            ],
            'supplier' => [
                'view'
            ],
            'product' => [
                'view',
                'add'
            ],
            'price' => [
                'view',
                'add'
            ],
            'stock' => [
                'view',
                'add'
            ],
            'report' => [
                'view',
            ]
        ];
        $role = new Role();
        $role->role_name = 'Staff';
        $role->access_items = json_encode($roleList);
        $role->save();
    }
}
