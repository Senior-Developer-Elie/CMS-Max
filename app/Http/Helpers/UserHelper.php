<?php
namespace App\Http\Helpers;

class UserHelper {

    protected static $allPermissions = [
        [
            'name'      => 'Blog Dashboard',
            'icon'      => 'fab fa-blogger-b',
            'link'      => '/blog-dashboard',
            'section'   => 'blog-dashboard'
        ],
        /*[
            'name'      => 'Jobs To Do',
            'icon'      => 'fa fa-tasks',
            'link'      => '/jobs',
            'section'   => 'inner-page'
        ],*/
        [
            'name'      => 'Clients',
            'icon'      => 'fa fa-users',
            'link'      => '/client-list',
            'section'   => 'client-list'
        ],
        [
            'name'      => 'Websites',
            'icon'      => 'fa fa-globe',
            'link'      => '/websites',
            'section'   => 'website-list'
        ],
        [
            'name'      => 'Websites in Progress',
            'icon'      => 'fa fa-server',
            'link'      => '/website-progress',
            'section'   => 'website-progress'
        ],
        [
            'name'      => 'Post-Live Checklist',
            'title'     => 'Search Console',
            'icon'      => 'far fa-check-square',
            'link'      => '/post-live-checklist',
            'section'   => 'post-live-checklist'
        ],
        [
            'name'      => 'History & Stats',
            'icon'      => 'fas fa-chart-bar',
            'link'      => '/website-completed',
            'section'   => 'website-completed'
        ],
        [
            'name'      => 'Social Media',
            'icon'      => 'fas fa-search-dollar',
            'link'      => '/social-media',
            'section'   => 'social-media'
        ],
        [
            'name'      => 'Marketing',
            'title'     => 'Google Ads',
            'icon'      => 'fas fa-bullhorn',
            'link'      => '/marketing',
            'section'   => 'marketing'
        ],
        [
            'name'      => 'Budgeting',
            'icon'      => 'fas fa-comment-dollar',
            'link'      => '/budgeting',
            'section'   => 'budgeting'
        ],
        [
            'name'      => 'Sales & Loss',
            'icon'      => 'fas fa-hand-holding-usd',
            'link'      => '/financial-reports',
            'section'   => 'profit-loss'
        ],
        [
            'name'      => 'Billing',
            'icon'      => 'fas fa-search-dollar',
            'link'      => '/billing',
            'section'   => 'billing'
        ],
        [
            'name'      => 'Admin History',
            'icon'      => 'fa fa-history',
            'link'      => '/admin-history',
            'section'   => 'admin-history'
        ],
        [
            'name'      => 'Credit Card Processing',
            'icon'      => 'far fa-credit-card',
            'link'      => '/credit-card-processing',
            'section'   => 'credit-card-processing'
        ],
        // [
        //     'name'      => 'Payroll',
        //     'icon'      => 'fas fa-cash-register',
        //     'link'      => '/payroll',
        //     'section'   => 'website-payroll'
        // ],
        [
            'name'      => 'Proposals List',
            'icon'      => 'far fa-file-pdf',
            'link'      => '/proposal-list',
            'section'   => 'proposal-list'
        ],
        // [
        //     'name'          => 'Failed Mails',
        //     'icon'          => 'fas fa-ban',
        //     'link'          => '/failed-mails',
        //     'section'       => 'failed-mails',
        //     'badgeFunction' => 'failed-mails'
        // ],

        /*
        [
            'name'      => 'QuickBooks Import',
            'icon'      => 'fa fa-book',
            'link'      => '/quickbooks-import',
            'section'   => 'quickbooks-import'
        ],*/



        [
            'name'          => 'Tools',
            'icon'          => 'fas fa-tools',
            'subPages'      => [
                [
                    'name'          => 'pdf converter',
                    'title'         => 'PDF to Image Converter',
                    'icon'          => 'far fa-file-pdf',
                    'link'          => 'http://convert.cmsmax.com/',
                    'section'       => 'pdf-to-image',
                    'target'        => '_blank',
                    'badgeContent'  => '<span class="right badge"><i class="fas fa-external-link-alt"></i></span>'
                ],
                [
                    'name'          => 'image resize',
                    'title'         => 'Image Resize',
                    'icon'          => 'fas fa-crop',
                    'link'          => 'http://bulkimageeditor.com/',
                    'section'       => 'pdf-to-image',
                    'target'        => '_blank',
                    'badgeContent'  => '<span class="right badge"><i class="fas fa-external-link-alt"></i></span>'
                ],
                [
                    'name'          => 'calculate card',
                    'title'         => 'CC Comparison',
                    'icon'          => 'fas fa-calculator',
                    'link'          => '/calculate-card-rate',
                    'section'       => 'calculate-card',
                ],
                [
                    'name'          => 'mockup generator',
                    'title'         => 'Mockup Generator',
                    'icon'          => 'far fa-images',
                    'link'          => '/mockups/create',
                    'section'       => 'mockup-generate',
                ],
            ]
        ],




        [
            'name'          => 'Settings',
            'icon'          => 'fa fa-cogs',
            'subPages'      => [
                [
                    'name'      => 'Manage P&L Profits',
                    'icon'      => 'fas fa-dollar-sign',
                    'link'      => '/manage-profit',
                    'section'   => 'manage-profit'
                ],
                [
                    'name'      => 'Manage P&L Expenses',
                    'icon'      => 'fas fa-dollar-sign',
                    'link'      => '/manage-expense',
                    'section'   => 'manage-expense'
                ],
                [
                    'name'      => 'Manage Default Text',
                    'title'     => 'Manage Proposal Text',
                    'icon'      => 'fa fa-edit',
                    'link'      => '/manage-default-text',
                    'section'   => 'manage-default'
                ],
                // [
                //     'name'      => 'Manage Default Card Rate',
                //     'icon'      => 'fa fa-edit',
                //     'link'      => '/manage-default-rate',
                //     'section'   => 'manage-default-rate'
                // ],
                [
                    'name'      => 'Manage Website Sender',
                    'icon'      => 'fas fa-envelope-square',
                    'link'      => '/manage-website-sender',
                    'section'   => 'manage-sender'
                ],
                [
                    'name'      => 'Api Client List',
                    'icon'      => 'fab fa-quinscape',
                    'link'      => '/api-client-list',
                    'section'   => 'api-client-list'
                ],
                [
                    'name'      => 'Manage Industries',
                    'icon'      => 'fa fa-industry',
                    'link'      => '/blog-industries',
                    'section'   => 'blog-industries'
                ],
                [
                    'name'      => 'Manage Payment Gateways',
                    'icon'      => 'fa fa-cog',
                    'link'      => '/manage-paymentGateway',
                    'section'   => 'manage-paymentGateway'
                ],
                [
                    'name'      => 'Manage Shipping Methods',
                    'icon'      => 'fa fa-cog',
                    'link'      => '/manage-shippingMethod',
                    'section'   => 'manage-shippingMethod'
                ],
                [
                    'name'      => 'Manage Affiliates',
                    'icon'      => 'fa fa-cog',
                    'link'      => '/manage-affiliate',
                    'section'   => 'manage-affiliate'
                ],
                [
                    'name'      => 'Manage DNS',
                    'icon'      => 'fa fa-cog',
                    'link'      => '/manage-dns',
                    'section'   => 'manage-dns'
                ],
                [
                    'name'      => 'Manage Admins',
                    'icon'      => 'fa fa-users',
                    'link'      => '/users',
                    'section'   => 'manage-users'
                ],
                // [
                //     'name'      => 'Mailgun Api Keys',
                //     'icon'      => 'fa fa-mail-bulk',
                //     'link'      => '/mailgun-api-keys',
                //     'section'   => 'mailgun-api-keys'
                // ],
            ]
        ],
    ];

    public static function getAllPagePermissions(){
        return self::$allPermissions;
    }
}
