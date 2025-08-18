import { Permission } from '@/constants/Permission';

export default [
    {
        name: 'Dashboard',
        icon: 'fas fa-gauge',
        route: 'admin.dashboard',
        active: 'admin.dashboard',
        permission: Permission.IGNORE,
    },
    
    {
        name: 'User Management',
        icon: 'fas fa-users',
        route: 'admin.users.index',
        active: 'admin.users.*',
        permission: [
            Permission.USER_LIST,
            Permission.USER_VIEW,
            Permission.USER_EDIT,
        ],
    },
    {
        name: 'Categories',
        icon: 'fas fa-list',
        route: 'admin.categories.index',
        active: 'admin.categories.*',
        permission: [
            Permission.CATEGORY_LIST,
            Permission.CATEGORY_ADD,
            Permission.CATEGORY_EDIT,
            Permission.CATEGORY_VIEW,
            Permission.CATEGORY_DELETE,
        ],
    },
    {
        name: 'Content Pages',
        icon: 'fas fa-file-circle-plus',
        route: 'admin.content-pages.index',
        active: 'admin.content-pages.*',
        permission: [
            Permission.CMS_LIST,
            Permission.CMS_ADD,
            Permission.CMS_VIEW,
            Permission.CMS_EDIT,
            Permission.CMS_DELETE,
        ],
    },
    {
        name: 'Setting',
        isLabel: true,
        associatedMenus: ['app-settings', 'sub-admin-management'], // used to show/hide label based on permission
    },
    {
        name: 'App Settings',
        icon: 'fas fa-cogs',
        key: 'app-settings',
        menuItems: [
            {
                name: 'Mobile Management',
                route: 'admin.setting.mobile-version',
                active: 'admin.setting.mobile-version',
                permission: [
                    Permission.MOBILE_CONFIG_LIST,
                    Permission.MOBILE_CONFIG_EDIT,
                ],
            },
            {
                name: 'Smtp Configuration',
                route: 'admin.setting.smtp',
                active: 'admin.setting.smtp',
                permission: [
                    Permission.SMTP_CONFIG_LIST,
                    Permission.SMTP_CONFIG_EDIT,
                ],
            },
            {
                name: 'Seeders Management',
                route: 'admin.setting.seeder',
                active: 'admin.setting.seeder',
                permission: [Permission.SEEDER_LIST, Permission.SEEDER_EXECUTE],
            },
        ],
    },
    {
        name: 'Sub Admin Management',
        icon: 'fas fa-user-gear',
        key: 'sub-admin-management',
        menuItems: [
            {
                name: 'Sub Admins',
                route: 'admin.admins.index',
                active: 'admin.admins.*',
                permission: [
                    Permission.SUB_ADMIN_LIST,
                    Permission.SUB_ADMIN_ADD,
                    Permission.SUB_ADMIN_EDIT,
                    Permission.SUB_ADMIN_DELETE,
                ],
            },
            {
                name: 'Roles',
                route: 'admin.roles.index',
                active: 'admin.roles.*',
                permission: [
                    Permission.ROLE_LIST,
                    Permission.ROLE_ADD,
                    Permission.ROLE_EDIT,
                    Permission.ROLE_DELETE,
                ],
            },
            {
                name: 'Permissions',
                route: 'admin.permissions.index',
                active: 'admin.permissions.*',
                permission: [
                    Permission.PERMISSION_LIST,
                    Permission.PERMISSION_ADD,
                    Permission.PERMISSION_EDIT,
                    Permission.PERMISSION_DELETE,
                ],
            },
        ],
    },
];
