<?php

return [
    'roles' => [
        'admin' => 'Admin',
        'staff' => 'Staff',
    ],
    'groups' => [
        'rooms' => [
            'label' => 'Rooms',
            'permissions' => [
                'rooms.view' => 'View rooms',
                'rooms.manage' => 'Create, update, and delete rooms',
                'floors.manage' => 'Manage floors',
                'room_types.manage' => 'Manage room types',
                'facilities.manage' => 'Manage facilities',
            ],
        ],
        'customers' => [
            'label' => 'Customers',
            'permissions' => [
                'customers.view' => 'View customers',
                'customers.manage' => 'Create, update, and delete customers',
            ],
        ],
        'rentals' => [
            'label' => 'Rentals',
            'permissions' => [
                'rentals.view' => 'View availability and rented rooms',
                'rentals.manage' => 'Create, update, and check out rentals',
            ],
        ],
        'collections' => [
            'label' => 'Collections',
            'permissions' => [
                'collections.view' => 'View invoices and collections',
                'collections.manage' => 'Record payments and delete invoices',
            ],
        ],
        'journal' => [
            'label' => 'Journal Entries',
            'permissions' => [
                'journal.view' => 'View journal entries',
                'journal.manage' => 'Create, update, and delete journal entries',
            ],
        ],
        'reports' => [
            'label' => 'Reports',
            'permissions' => [
                'reports.view' => 'View reports',
            ],
        ],
        'administration' => [
            'label' => 'Administration',
            'permissions' => [
                'users.view' => 'View users list',
                'users.manage' => 'Create, update, and delete users',
                'permissions.manage' => 'Manage role permissions',
                'settings.manage' => 'Update application settings',
            ],
        ],
    ],
    'defaults' => [
        'staff' => [
            'rooms.view' => true,
            'rooms.manage' => true,
            'floors.manage' => true,
            'room_types.manage' => true,
            'facilities.manage' => true,
            'customers.view' => true,
            'customers.manage' => true,
            'rentals.view' => true,
            'rentals.manage' => true,
            'collections.view' => true,
            'collections.manage' => true,
            'journal.view' => true,
            'journal.manage' => true,
            'reports.view' => true,
            'settings.manage' => true,
            'users.view' => true,
            'users.manage' => false,
            'permissions.manage' => false,
        ],
    ],
];
