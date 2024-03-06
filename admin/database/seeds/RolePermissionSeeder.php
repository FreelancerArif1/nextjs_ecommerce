<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Create Roles
        $RoleSuperAdmin = Role::findOrCreate('superadmin', 'web');
        $RoleAdmin = Role::findOrCreate('admin', 'web');
        $RoleEditor = Role::findOrCreate('editor', 'web');
        $RoleModerator = Role::findOrCreate('moderator', 'web');
        $RoleSeller = Role::findOrCreate('seller', 'web');

        //Permission list Array

        $permissions = [

            //Admin Account Permission
            'admin.view',
            'admin.create',
            'admin.edit',
            'admin.delete',

            //Vendor Account Permission
            'vendor.view',
            'vendor.create',
            'vendor.edit',
            'vendor.delete',
            'vendor.accounts',

            //Customer Account Permission
            'customer.view',
            'customer.create',
            'customer.edit',
            'customer.delete',
            'customer.accounts',

            //Role Permission
            'role.view',
            'role.create',
            'role.edit',
            'role.delete',

            //Sliders
            'slider.view',
            'slider.create',
            'slider.edit',
            'slider.delete',

            //Navbas
            'navbar.view',
            'navbar.create',
            'navbar.edit',
            'navbar.delete',
            
            //Testimonials
            'testimonials.view',
            'testimonials.create',
            'testimonials.edit',
            'testimonials.delete',

            //Design & Settings
            'design.view',
            'design.create',
            'design.edit',
            'design.delete',

            //Settings
            'settings.view',
            'settings.create',
            'settings.edit',
            'settings.delete',
            

            //Search Dashboard
            'search.dashboard',
            'search.edit',
            'search.delete',

            //Environment
            'environment.view',
            'environment.edit',

            //Clean Cache
            'cache.view',
            'cache.create',

            //Currencies
            'currencies.view',
            'currencies.create',
            'currencies.edit',
            'currencies.delete',

            //Pages
            'pages.view',
            'pages.create',
            'pages.edit',
            'pages.delete',

            //Careers
            'career.view',
            'career.create',
            'career.edit',
            'career.delete',

            //Career Request
            'career.request',
            'career.request.delete',

            //Blog
            'blog.view',
            'blog.create',
            'blog.edit',
            'blog.delete',

            //Blog Category
            'blog.category.view',
            'blog.category.create',
            'blog.category.edit',
            'blog.category.delete',

            //Orders
            'order.view',
            'order.create',
            'order.edit',
            'order.delete',

            //Attribute Set
            'attributeset.view',
            'attributeset.create',
            'attributeset.edit',
            'attributeset.delete',

            //Attribute List
            'attributelist.view',
            'attributelist.create',
            'attributelist.edit',
            'attributelist.delete',

            //Product
            'product.view',
            'product.create',
            'product.edit',
            'product.delete',
            'product.qcapprove',
            'product.restock.request',
            'product.return.request',
            'product.restock.request.change.status',
            'product.return.request.pushnotification',
            'product.return.request.sms',

            //Category
            'category.view',
            'category.create',
            'category.edit',
            'category.delete',

            //Brand
            'brand.view',
            'brand.create',
            'brand.edit',
            'brand.delete',

            //Custom Option
            'customoption.view',
            'customoption.create',
            'customoption.edit',
            'customoption.delete',

            //Report
            'report.view',
            'report.create',
            'report.edit',
            'report.delete',
            'report.coupon.uses',

            'report.sales',
            'report.products.sales',
            'report.balance.history',
            'report.category.sales',
            'report.seller.products',
            'report.single.product',
            'report.sales.confirm.status',
            'report.products.wishlist',
            'report.top.sold.products',
            'report.low.stock.item',
            'report.vat',



            //Language
            'language.view',
            'language.create',
            'language.edit',
            'language.delete',

            //Notification
            'notification.view',
            'notification.create',
            'notification.edit',
            'notification.delete',

            //Marketing
            'marketing.view',
            'marketing.create',
            'marketing.push.notification',
            'marketing.bulk.message',
            'marketing.user.search',
            'marketing.subscriber',

            //review
            'review.view',
            'review.create',
            'review.edit',
            'review.delete',
            'review.replay',

            //Dashboard
            'dashboard.view',

            //Gallery
            'gallery.view',

            //POS
            'pos.view',
            'pos.create',
            'pos.edit',
            'pos.delete',


            //bulk import
            'import.view',
            'import.create',
            'import.edit',
            'import.delete',

            //pick Point
            'pick_point.view',
            'pick_point.create',
            'pick_point.edit',
            'pick_point.delete',


            //activitylog
            'activitylog.view',
            'activitylog.delete',

            //Trash List
            'trash.view',
            'trash.create',
            'trash.edit',
            'trash.delete',

            //coupons
            'coupon.view',
            'coupon.create',
            'coupon.edit',
            'coupon.delete',

            //voucher
            'voucher.view',
            'voucher.create',
            'voucher.edit',
            'voucher.delete',

            //location
            'location.view',
            'location.create',
            'location.edit',
            'location.delete',

            //orderautorenewal
            'orderautorenewal.view',
            'orderautorenewal.create',
            'orderautorenewal.edit',
            'orderautorenewal.delete',

            //Tickets
            'ticket.view',
            'ticket.create',
            'ticket.edit',
            'ticket.delete',
            'ticket.replay',

            //flash_deals
            'flash_deals.view',
            'flash_deals.create',
            'flash_deals.edit',
            'flash_deals.delete',
            'flash_deals.copy',
            'flash_deals.pushnotification',
            'flash_deals.sms',

            //Affiliate
            'affiliate.view',
            'affiliate.change.status',
            'affiliate.delete',

            'affiliate.withdrawl.view',
            'affiliate.withdrawl.change.status',
            'affiliate.withdrawl.delete',


        ];


        $allPermissions = Permission::all();

        //Create & Assign Permissions
        foreach ($permissions as $permission) {
            //Create Permission
            $permission = Permission::findOrCreate($permission, 'web');
            $RoleSuperAdmin->givePermissionTo($permission);
            $permission->assignRole($RoleSuperAdmin);
        }
    }
}