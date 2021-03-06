<?php

declare(strict_types=1);

use Tipoff\Authorization\Permissions\BasePermissionsMigration;

class AddCheckoutPermissions extends BasePermissionsMigration
{
    public function up()
    {
        $permissions = [
            'view carts' => ['Owner', 'Executive', 'Staff'],
            'view cart items' => ['Owner', 'Executive', 'Staff'],
            'view orders' => ['Owner', 'Executive', 'Staff'],
            'view order items' => ['Owner', 'Executive', 'Staff'],
        ];
        
        $this->createPermissions($permissions);
    }
}
