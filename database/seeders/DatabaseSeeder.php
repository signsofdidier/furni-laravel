<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductColorStock;
use App\Models\Review;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('shield:install', ['panel' => 'admin']); // Installeer Shield volledig

// Alle permissies aanmaken
        Permission::firstOrCreate(['name' => 'create_blog']);
        Permission::firstOrCreate(['name' => 'create_brand']);
        Permission::firstOrCreate(['name' => 'create_category']);
        Permission::firstOrCreate(['name' => 'create_color']);
        Permission::firstOrCreate(['name' => 'create_order']);
        Permission::firstOrCreate(['name' => 'create_product']);
        Permission::firstOrCreate(['name' => 'create_product::color::stock']);
        Permission::firstOrCreate(['name' => 'create_review']);
        Permission::firstOrCreate(['name' => 'create_role']);
        Permission::firstOrCreate(['name' => 'create_setting']);
        Permission::firstOrCreate(['name' => 'create_user']);
        Permission::firstOrCreate(['name' => 'delete_any_blog']);
        Permission::firstOrCreate(['name' => 'delete_any_brand']);
        Permission::firstOrCreate(['name' => 'delete_any_category']);
        Permission::firstOrCreate(['name' => 'delete_any_color']);
        Permission::firstOrCreate(['name' => 'delete_any_order']);
        Permission::firstOrCreate(['name' => 'delete_any_product']);
        Permission::firstOrCreate(['name' => 'delete_any_product::color::stock']);
        Permission::firstOrCreate(['name' => 'delete_any_review']);
        Permission::firstOrCreate(['name' => 'delete_any_role']);
        Permission::firstOrCreate(['name' => 'delete_any_setting']);
        Permission::firstOrCreate(['name' => 'delete_any_user']);
        Permission::firstOrCreate(['name' => 'delete_blog']);
        Permission::firstOrCreate(['name' => 'delete_brand']);
        Permission::firstOrCreate(['name' => 'delete_category']);
        Permission::firstOrCreate(['name' => 'delete_color']);
        Permission::firstOrCreate(['name' => 'delete_order']);
        Permission::firstOrCreate(['name' => 'delete_product']);
        Permission::firstOrCreate(['name' => 'delete_product::color::stock']);
        Permission::firstOrCreate(['name' => 'delete_review']);
        Permission::firstOrCreate(['name' => 'delete_role']);
        Permission::firstOrCreate(['name' => 'delete_setting']);
        Permission::firstOrCreate(['name' => 'delete_user']);
        Permission::firstOrCreate(['name' => 'force_delete_any_blog']);
        Permission::firstOrCreate(['name' => 'force_delete_any_brand']);
        Permission::firstOrCreate(['name' => 'force_delete_any_category']);
        Permission::firstOrCreate(['name' => 'force_delete_any_color']);
        Permission::firstOrCreate(['name' => 'force_delete_any_order']);
        Permission::firstOrCreate(['name' => 'force_delete_any_product']);
        Permission::firstOrCreate(['name' => 'force_delete_any_product::color::stock']);
        Permission::firstOrCreate(['name' => 'force_delete_any_review']);
        Permission::firstOrCreate(['name' => 'force_delete_any_setting']);
        Permission::firstOrCreate(['name' => 'force_delete_any_user']);
        Permission::firstOrCreate(['name' => 'force_delete_blog']);
        Permission::firstOrCreate(['name' => 'force_delete_brand']);
        Permission::firstOrCreate(['name' => 'force_delete_category']);
        Permission::firstOrCreate(['name' => 'force_delete_color']);
        Permission::firstOrCreate(['name' => 'force_delete_order']);
        Permission::firstOrCreate(['name' => 'force_delete_product']);
        Permission::firstOrCreate(['name' => 'force_delete_product::color::stock']);
        Permission::firstOrCreate(['name' => 'force_delete_review']);
        Permission::firstOrCreate(['name' => 'force_delete_setting']);
        Permission::firstOrCreate(['name' => 'force_delete_user']);
        Permission::firstOrCreate(['name' => 'reorder_blog']);
        Permission::firstOrCreate(['name' => 'reorder_brand']);
        Permission::firstOrCreate(['name' => 'reorder_category']);
        Permission::firstOrCreate(['name' => 'reorder_color']);
        Permission::firstOrCreate(['name' => 'reorder_order']);
        Permission::firstOrCreate(['name' => 'reorder_product']);
        Permission::firstOrCreate(['name' => 'reorder_product::color::stock']);
        Permission::firstOrCreate(['name' => 'reorder_review']);
        Permission::firstOrCreate(['name' => 'reorder_setting']);
        Permission::firstOrCreate(['name' => 'reorder_user']);
        Permission::firstOrCreate(['name' => 'replicate_blog']);
        Permission::firstOrCreate(['name' => 'replicate_brand']);
        Permission::firstOrCreate(['name' => 'replicate_category']);
        Permission::firstOrCreate(['name' => 'replicate_color']);
        Permission::firstOrCreate(['name' => 'replicate_order']);
        Permission::firstOrCreate(['name' => 'replicate_product']);
        Permission::firstOrCreate(['name' => 'replicate_product::color::stock']);
        Permission::firstOrCreate(['name' => 'replicate_review']);
        Permission::firstOrCreate(['name' => 'replicate_setting']);
        Permission::firstOrCreate(['name' => 'replicate_user']);
        Permission::firstOrCreate(['name' => 'restore_any_blog']);
        Permission::firstOrCreate(['name' => 'restore_any_brand']);
        Permission::firstOrCreate(['name' => 'restore_any_category']);
        Permission::firstOrCreate(['name' => 'restore_any_color']);
        Permission::firstOrCreate(['name' => 'restore_any_order']);
        Permission::firstOrCreate(['name' => 'restore_any_product']);
        Permission::firstOrCreate(['name' => 'restore_any_product::color::stock']);
        Permission::firstOrCreate(['name' => 'restore_any_review']);
        Permission::firstOrCreate(['name' => 'restore_any_setting']);
        Permission::firstOrCreate(['name' => 'restore_any_user']);
        Permission::firstOrCreate(['name' => 'restore_blog']);
        Permission::firstOrCreate(['name' => 'restore_brand']);
        Permission::firstOrCreate(['name' => 'restore_category']);
        Permission::firstOrCreate(['name' => 'restore_color']);
        Permission::firstOrCreate(['name' => 'restore_order']);
        Permission::firstOrCreate(['name' => 'restore_product']);
        Permission::firstOrCreate(['name' => 'restore_product::color::stock']);
        Permission::firstOrCreate(['name' => 'restore_review']);
        Permission::firstOrCreate(['name' => 'restore_setting']);
        Permission::firstOrCreate(['name' => 'restore_user']);
        Permission::firstOrCreate(['name' => 'update_blog']);
        Permission::firstOrCreate(['name' => 'update_brand']);
        Permission::firstOrCreate(['name' => 'update_category']);
        Permission::firstOrCreate(['name' => 'update_color']);
        Permission::firstOrCreate(['name' => 'update_order']);
        Permission::firstOrCreate(['name' => 'update_product']);
        Permission::firstOrCreate(['name' => 'update_product::color::stock']);
        Permission::firstOrCreate(['name' => 'update_review']);
        Permission::firstOrCreate(['name' => 'update_role']);
        Permission::firstOrCreate(['name' => 'update_setting']);
        Permission::firstOrCreate(['name' => 'update_user']);
        Permission::firstOrCreate(['name' => 'view_any_blog']);
        Permission::firstOrCreate(['name' => 'view_any_brand']);
        Permission::firstOrCreate(['name' => 'view_any_category']);
        Permission::firstOrCreate(['name' => 'view_any_color']);
        Permission::firstOrCreate(['name' => 'view_any_order']);
        Permission::firstOrCreate(['name' => 'view_any_product']);
        Permission::firstOrCreate(['name' => 'view_any_product::color::stock']);
        Permission::firstOrCreate(['name' => 'view_any_review']);
        Permission::firstOrCreate(['name' => 'view_any_role']);
        Permission::firstOrCreate(['name' => 'view_any_setting']);
        Permission::firstOrCreate(['name' => 'view_any_user']);
        Permission::firstOrCreate(['name' => 'view_blog']);
        Permission::firstOrCreate(['name' => 'view_brand']);
        Permission::firstOrCreate(['name' => 'view_category']);
        Permission::firstOrCreate(['name' => 'view_color']);
        Permission::firstOrCreate(['name' => 'view_order']);
        Permission::firstOrCreate(['name' => 'view_product']);
        Permission::firstOrCreate(['name' => 'view_product::color::stock']);
        Permission::firstOrCreate(['name' => 'view_review']);
        Permission::firstOrCreate(['name' => 'view_role']);
        Permission::firstOrCreate(['name' => 'view_setting']);
        Permission::firstOrCreate(['name' => 'view_user']);
        Permission::firstOrCreate(['name' => 'widget_DashboardStats']);
        Permission::firstOrCreate(['name' => 'widget_LatestOrders']);
        Permission::firstOrCreate(['name' => 'widget_OrderStats']);
        // Address Permissions
        Permission::firstOrCreate(['name' => 'view_address']);
        Permission::firstOrCreate(['name' => 'view_any_address']);
        Permission::firstOrCreate(['name' => 'create_address']);
        Permission::firstOrCreate(['name' => 'update_address']);
        Permission::firstOrCreate(['name' => 'delete_address']);
        Permission::firstOrCreate(['name' => 'delete_any_address']);
        Permission::firstOrCreate(['name' => 'restore_address']);
        Permission::firstOrCreate(['name' => 'restore_any_address']);
        Permission::firstOrCreate(['name' => 'force_delete_address']);
        Permission::firstOrCreate(['name' => 'force_delete_any_address']);

        // Rollen en gebruikers aanmaken

        $role = Role::firstOrCreate(['name' => 'blog_author', 'guard_name' => 'web']);
        $role->syncPermissions(['view_blog', 'view_any_blog', 'create_blog', 'update_blog']);
        User::factory()->create([
            'name' => 'Blog Author',
            'email' => 'blog_author@gmail.com',
            'password' => Hash::make('password'),
        ])->assignRole('blog_author');

        $role = Role::firstOrCreate(['name' => 'content_editor', 'guard_name' => 'web']);
        $role->syncPermissions(['view_blog', 'view_any_blog', 'create_blog', 'update_blog', 'restore_blog', 'restore_any_blog', 'delete_blog', 'delete_any_blog', 'view_brand', 'view_any_brand', 'view_category', 'view_any_category', 'create_category', 'update_category', 'restore_category', 'restore_any_category', 'widget_DashboardStats']);
        User::factory()->create([
            'name' => 'Content Editor',
            'email' => 'content_editor@gmail.com',
            'password' => Hash::make('password'),
        ])->assignRole('content_editor');

        $role = Role::firstOrCreate(['name' => 'customer_service', 'guard_name' => 'web']);
        $role->syncPermissions(['view_order', 'view_any_order', 'view_user', 'view_any_user', 'view_address', 'view_any_address']);
        User::factory()->create([
            'name' => 'Customer Service',
            'email' => 'customer_service@gmail.com',
            'password' => Hash::make('password'),
        ])->assignRole('customer_service');

        $role = Role::firstOrCreate(['name' => 'product_manager', 'guard_name' => 'web']);
        $role->syncPermissions(['view_brand', 'view_any_brand', 'create_brand', 'update_brand', 'restore_brand', 'restore_any_brand', 'delete_brand', 'delete_any_brand', 'force_delete_brand', 'force_delete_any_brand', 'view_category', 'view_any_category', 'create_category', 'update_category', 'restore_category', 'restore_any_category', 'delete_category', 'delete_any_category', 'force_delete_category', 'force_delete_any_category', 'view_color', 'view_any_color', 'create_color', 'update_color', 'restore_color', 'restore_any_color', 'delete_color', 'delete_any_color', 'force_delete_color', 'force_delete_any_color', 'view_order', 'view_any_order', 'create_order', 'update_order', 'restore_order', 'restore_any_order', 'delete_order', 'delete_any_order', 'force_delete_order', 'force_delete_any_order', 'view_product', 'view_any_product', 'create_product', 'update_product', 'restore_product', 'restore_any_product', 'delete_product', 'delete_any_product', 'force_delete_product', 'force_delete_any_product', 'view_product::color::stock', 'view_any_product::color::stock', 'create_product::color::stock', 'update_product::color::stock', 'restore_product::color::stock', 'restore_any_product::color::stock', 'delete_product::color::stock', 'delete_any_product::color::stock', 'force_delete_product::color::stock', 'force_delete_any_product::color::stock', 'widget_OrderStats', 'widget_DashboardStats', 'widget_LatestOrders']);
        User::factory()->create([
            'name' => 'Product Manager',
            'email' => 'product_manager@gmail.com',
            'password' => Hash::make('password'),
        ])->assignRole('product_manager');

        $role = Role::firstOrCreate(['name' => 'review_moderator', 'guard_name' => 'web']);
        $role->syncPermissions(['view_review', 'view_any_review', 'create_review', 'update_review', 'restore_review', 'restore_any_review', 'delete_review', 'delete_any_review', 'force_delete_review', 'force_delete_any_review', 'widget_DashboardStats']);
        User::factory()->create([
            'name' => 'Review Moderator',
            'email' => 'review_moderator@gmail.com',
            'password' => Hash::make('password'),
        ])->assignRole('review_moderator');

        $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $role->syncPermissions(['view_role', 'view_any_role', 'create_role', 'update_role', 'delete_role', 'delete_any_role', 'view_blog', 'view_any_blog', 'create_blog', 'update_blog', 'restore_blog', 'restore_any_blog', 'replicate_blog', 'reorder_blog', 'delete_blog', 'delete_any_blog', 'force_delete_blog', 'force_delete_any_blog', 'view_brand', 'view_any_brand', 'create_brand', 'update_brand', 'restore_brand', 'restore_any_brand', 'replicate_brand', 'reorder_brand', 'delete_brand', 'delete_any_brand', 'force_delete_brand', 'force_delete_any_brand', 'view_category', 'view_any_category', 'create_category', 'update_category', 'restore_category', 'restore_any_category', 'replicate_category', 'reorder_category', 'delete_category', 'delete_any_category', 'force_delete_category', 'force_delete_any_category', 'view_color', 'view_any_color', 'create_color', 'update_color', 'restore_color', 'restore_any_color', 'replicate_color', 'reorder_color', 'delete_color', 'delete_any_color', 'force_delete_color', 'force_delete_any_color', 'view_order', 'view_any_order', 'create_order', 'update_order', 'restore_order', 'restore_any_order', 'replicate_order', 'reorder_order', 'delete_order', 'delete_any_order', 'force_delete_order', 'force_delete_any_order', 'view_product', 'view_any_product', 'create_product', 'update_product', 'restore_product', 'restore_any_product', 'replicate_product', 'reorder_product', 'delete_product', 'delete_any_product', 'force_delete_product', 'force_delete_any_product', 'view_product::color::stock', 'view_any_product::color::stock', 'create_product::color::stock', 'update_product::color::stock', 'restore_product::color::stock', 'restore_any_product::color::stock', 'replicate_product::color::stock', 'reorder_product::color::stock', 'delete_product::color::stock', 'delete_any_product::color::stock', 'force_delete_product::color::stock', 'force_delete_any_product::color::stock', 'view_review', 'view_any_review', 'create_review', 'update_review', 'restore_review', 'restore_any_review', 'replicate_review', 'reorder_review', 'delete_review', 'delete_any_review', 'force_delete_review', 'force_delete_any_review', 'view_setting', 'view_any_setting', 'create_setting', 'update_setting', 'restore_setting', 'restore_any_setting', 'replicate_setting', 'reorder_setting', 'delete_setting', 'delete_any_setting', 'force_delete_setting', 'force_delete_any_setting', 'view_user', 'view_any_user', 'create_user', 'update_user', 'restore_user', 'restore_any_user', 'replicate_user', 'reorder_user', 'delete_user', 'delete_any_user', 'force_delete_user', 'force_delete_any_user', 'widget_OrderStats', 'widget_DashboardStats', 'widget_LatestOrders']);
        User::factory()->create([
            'name' => 'Didier Vanassche',
            'email' => 'didier.v@hotmail.com',
            'password' => Hash::make('password'),
        ])->assignRole('super_admin');

        // 2. Roles & Permissions
        $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $role->syncPermissions(Permission::all()); // Alle permissies

        // 3. Gebruiker maken en rol toewijzen
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin', 'password' => Hash::make('password'), 'email_verified_at' => now(),]
        );
        $admin->assignRole($role);

        User::firstOrCreate(
            ['email' => 'jan@gmail.com'],
            ['name' => 'Jan Dorie', 'password' => Hash::make('password'), 'email_verified_at' => now(),]
        );

        User::firstOrCreate(
            ['email' => 'sophie@gmail.com'],
            ['name' => 'Sophie Adams', 'password' => Hash::make('password'), 'email_verified_at' => now(),]
        );

        User::firstOrCreate(
            ['email' => 'charles@gmail.com'],
            ['name' => 'Charles Peters', 'password' => Hash::make('password'), 'email_verified_at' => now(),]
        );




        // BRANDS
        $brands = ['Designo', 'SitWell', 'NordicHome', 'UrbanCraft', 'VintageVibe'];
        foreach ($brands as $brand) {
            Brand::create(['name' => $brand, 'slug' => Str::slug($brand)]);
        }

        // CATEGORIES
        $categories = ['Chairs', 'Sofas', 'Tables', 'Coffee Tables', 'Cabinets', 'Cupboards', 'Accessories'];
        foreach ($categories as $cat) {
            Category::create(['name' => $cat, 'slug' => Str::slug($cat)]);
        }

        // COLORS
        $colors = [
            ['name' => 'Black',        'hex' => '#000000'],
            ['name' => 'White Broken', 'hex' => '#F5F5F5'],
            ['name' => 'Turquoise',    'hex' => '#1DE9B6'],
            ['name' => 'Green Lime',   'hex' => '#C6FF00'],
            ['name' => 'Taupe',        'hex' => '#483C32'],
            ['name' => 'Walnut',       'hex' => '#77604E'],
        ];
        foreach ($colors as $color) {
            Color::create($color);
        }

        // PRODUCTS
        $products = [
            [
                'name' => 'Nordic Lounge Chair',
                'brand_id' => 3,
                'category_id' => 1,
                'description' => 'Comfortable lounge chair with Scandinavian design, perfect for any modern interior.',
                'price' => 199.99,
                'is_active' => true,
                'is_featured' => true,
                'in_stock' => true,
                'on_sale' => false,
                'colors' => [1, 2, 5],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'UrbanCraft Coffee Table',
                'brand_id' => 4,
                'category_id' => 4,
                'description' => 'Modern coffee table with a metal frame and oak wood top.',
                'price' => 349.00,
                'is_active' => true,
                'is_featured' => false,
                'in_stock' => true,
                'on_sale' => true,
                'colors' => [5, 6],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'Designo 3-Seat Sofa',
                'brand_id' => 1,
                'category_id' => 2,
                'description' => 'Luxurious 3-seater sofa upholstered in soft fabric. Available in several trendy colors.',
                'price' => 899.50,
                'is_active' => true,
                'is_featured' => true,
                'in_stock' => true,
                'on_sale' => true,
                'colors' => [2, 3, 4],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'SitWell Dining Table',
                'brand_id' => 2,
                'category_id' => 3,
                'description' => 'Sturdy dining table with a minimalist design, seats up to six people.',
                'price' => 599.00,
                'is_active' => true,
                'is_featured' => false,
                'in_stock' => false,
                'on_sale' => false,
                'colors' => [1, 2, 5, 6],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'VintageVibe Cabinet',
                'brand_id' => 5,
                'category_id' => 5,
                'description' => 'Stylish cabinet in vintage style, ideal for your hallway or living room.',
                'price' => 279.99,
                'is_active' => true,
                'is_featured' => true,
                'in_stock' => true,
                'on_sale' => false,
                'colors' => [5, 6, 4],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'Taupe Serenity Armchair',
                'brand_id' => 5,
                'category_id' => 1,
                'description' => 'A cozy armchair in elegant taupe with walnut wooden legs. Perfect for reading nooks or as an accent chair.',
                'price' => 269.00,
                'is_active' => true,
                'is_featured' => false,
                'in_stock' => true,
                'on_sale' => true,
                'colors' => [5, 6],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'Lime Green Modern Sofa',
                'brand_id' => 2,
                'category_id' => 2,
                'description' => 'Contemporary sofa with vibrant lime green fabric. Comfortable seating for your living room.',
                'price' => 799.00,
                'is_active' => true,
                'is_featured' => false,
                'in_stock' => true,
                'on_sale' => false,
                'colors' => [4, 2],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'Turquoise Dining Table',
                'brand_id' => 3,
                'category_id' => 3,
                'description' => 'Unique dining table with a smooth turquoise finish, perfect for a stylish and lively dining area.',
                'price' => 689.50,
                'is_active' => true,
                'is_featured' => false,
                'in_stock' => false,
                'on_sale' => false,
                'colors' => [3, 2],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'Minimalist Walnut Coffee Table',
                'brand_id' => 4,
                'category_id' => 4,
                'description' => 'A minimalist coffee table with a rich walnut top and sturdy black legs. Ideal for modern interiors.',
                'price' => 325.00,
                'is_active' => true,
                'is_featured' => false,
                'in_stock' => true,
                'on_sale' => true,
                'colors' => [6, 1],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'Oak & Taupe Storage Cabinet',
                'brand_id' => 1,
                'category_id' => 5,
                'description' => 'Functional storage cabinet combining oak and taupe for a timeless look. Plenty of space for your essentials.',
                'price' => 499.99,
                'is_active' => true,
                'is_featured' => false,
                'in_stock' => true,
                'on_sale' => false,
                'colors' => [5, 2],
                'shipping_cost' => 65.00,
            ],
        ];

        // IMAGES
        $localImages = [
            'chairs'   => ['plantkast-1.jpg', 'plantkast-2.jpg', 'plantkast-3.jpg'],
            'sofas'    => ['zetel-1.jpg', 'zetel-2.jpg', 'zetel-3.jpg', 'zetel-4.jpg'],
            'tables'   => ['table-1.jpg', 'table-2.jpg', 'table-3.jpg'],
            'coffee'   => ['salontafel.jpg'],
            'cabinets' => ['kast-1.jpg', 'kast-2.jpg', 'kast-3.jpg'],
            'default'  => ['comode-1.jpg', 'comode-2.jpg', 'comode-3.jpg'],
        ];

        foreach ($products as $productData) {
            $name = strtolower($productData['name']);

            if (str_contains($name, 'chair')) {
                $imgs = $localImages['chairs'];
            } elseif (str_contains($name, 'sofa')) {
                $imgs = $localImages['sofas'];
            } elseif (str_contains($name, 'table')) {
                $imgs = (str_contains($name, 'coffee')) ? $localImages['coffee'] : $localImages['tables'];
            } elseif (str_contains($name, 'cabinet')) {
                $imgs = $localImages['cabinets'];
            } else {
                $imgs = $localImages['default'];
            }

            $imgs = array_slice($imgs, 0, rand(1, count($imgs)));
            $imgs = array_map(fn($img) => 'products/' . ltrim($img, '/'), $imgs);

            $product = Product::create([
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'brand_id' => $productData['brand_id'],
                'category_id' => $productData['category_id'],
                'images' => $imgs,
                'description' => $productData['description'],
                'price' => $productData['price'],
                'is_active' => $productData['is_active'],
                'is_featured' => $productData['is_featured'],
                'in_stock' => $productData['in_stock'],
                'on_sale' => $productData['on_sale'],
                'shipping_cost' => $productData['shipping_cost'],
            ]);

            $product->colors()->attach($productData['colors']);

            // STOCK per kleur
            // Bepaal of dit product uitverkocht moet zijn (max 2)
            static $outOfStockCount = 0;
            $forceOutOfStock = $outOfStockCount < 2 && rand(0, 1) === 1;

            foreach ($productData['colors'] as $colorId) {
                $stock = $forceOutOfStock ? 0 : rand(1, 20);

                ProductColorStock::create([
                    'product_id' => $product->id,
                    'color_id' => $colorId,
                    'stock' => $stock,
                ]);
            }

            if ($forceOutOfStock) {
                $outOfStockCount++;
            }

            // REVIEWS
            $reviewCount = rand(0, 4);
            $availableUserIds = [1, 2, 3, 4];
            shuffle($availableUserIds);

            for ($i = 0; $i < $reviewCount; $i++) {
                $userId = $availableUserIds[$i] ?? null;
                if (!$userId) break;

                static $unapprovedReviewCount = 0;
                $isApproved = true;

                if ($unapprovedReviewCount < 3 && rand(0, 1) === 1) {
                    $isApproved = false;
                    $unapprovedReviewCount++;
                }

                // Betere verdeling van ratings
                // Gebalanceerde ratingverdeling
                $chance = rand(1, 100);
                if ($chance <= 10) {
                    $rating = 1;
                } elseif ($chance <= 25) {
                    $rating = 2;
                } elseif ($chance <= 45) {
                    $rating = 3;
                } elseif ($chance <= 70) {
                    $rating = 4;
                } else {
                    $rating = 5;
                }

                Review::create([
                    'product_id' => $product->id,
                    'user_id' => $userId,
                    'rating' => $rating,
                    'title' => fake()->sentence(),
                    'body' => fake()->paragraph(2),
                    'approved' => $isApproved,
                ]);
            }

        }
    }
}
