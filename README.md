<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## FoodTech Backend

This project is a **multi-tenant food delivery backend** built with Laravel 12, PostgreSQL, Filament, Stancl Tenancy, and Spatie Permission. It is designed for rapid development of food delivery platforms supporting restaurant chains, customers, drivers, and advanced business logic.

### Features
- Multi-tenancy (Stancl Tenancy)
- Role & permission management (Spatie Laravel Permission)
- Modern admin panel (Filament)
- Modular and scalable database structure for:
  - User & tenant management
  - Restaurants, branches, menus, orders
  - Delivery, reviews, promotions, payments, notifications, and logging

### Database Structure
See `/database/migrations/` for the full schema. Main modules:
- Multi-tenancy: tenants, users, roles, permissions
- Business: food_chains, restaurants, restaurant_branches
- Menu: menu_categories, menu_items, menu_item_variants
- Customers: customer_profiles, customer_addresses
- Orders: orders, order_items, order_item_variants
- Delivery: drivers, deliveries
- Reviews, coupons, coupon_usages, payment_transactions, notifications, activity_log

### Setup Instructions
1. **Clone & Install**
   ```bash
   git clone <repo-url>
   cd foodtech-backend
   composer install
   cp .env.example .env
   # Configure your DB connection in .env
   php artisan key:generate
   ```
2. **Install JS dependencies** (if needed)
   ```bash
   npm install && npm run build
   ```
3. **Run Migrations**
   ```bash
   php artisan migrate
   ```
4. **Install Packages**
   - [Spatie Permission](https://spatie.be/docs/laravel-permission/v6/introduction)
   - [Stancl Tenancy](https://tenancyforlaravel.com/docs/introduction/)
   - [Filament Admin](https://filamentphp.com/docs/3.x/admin/installation)

### Package Configuration
- **Stancl Tenancy:** See `config/tenancy.php` for tenant setup. Models use `tenant_id` for scoping.
- **Spatie Permission:** See `config/permission.php` for guard and model settings. Roles/permissions are ready for seeding.
- **Filament:** Admin panel is ready for resource generation for all major models.

### Next Steps
- Generate Filament Resources for admin CRUD UI
- Create Model Factories & Seeders for demo/test data
- Configure tenant-aware routes and middleware
- Set up authentication scaffolding (Breeze, Jetstream, or Fortify)

---
For more, see the `/app/Models/` directory and the migrations for details on relationships and architecture.

## Credits

**FoodTech Backend** is developed and maintained by John Gerwin De las Alas.

This project is built for the community as a foundation for modern, scalable, multi-tenant food delivery platforms. Contributions, suggestions, and forks are welcome!

## License

This software is released as **CopyFree** software by John Gerwin De las Alas.

You are free to use, modify, distribute, and build upon this project for any purpose, commercial or non-commercial, without restriction.

Attribution is appreciated but not required.

---

For questions, improvements, or to get in touch, please contact John Gerwin De las Alas.
