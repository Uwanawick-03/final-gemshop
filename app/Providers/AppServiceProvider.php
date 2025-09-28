<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Safely ensure default roles exist and have all permissions
        try {
            if (Schema::hasTable('roles') && Schema::hasTable('permissions') && Schema::hasTable('role_has_permissions')) {
                $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => config('auth.defaults.guard', 'web')]);
                $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => config('auth.defaults.guard', 'web')]);

                $allPermissions = Permission::all();
                if ($allPermissions->isNotEmpty()) {
                    $admin->syncPermissions($allPermissions);
                    $manager->syncPermissions($allPermissions);
                }

                // Auto-assign any newly created permission to admin and manager
                Permission::created(function (Permission $permission) use ($admin, $manager) {
                    $admin->givePermissionTo($permission);
                    $manager->givePermissionTo($permission);
                });
            }
        } catch (\Throwable $e) {
            // Silently ignore during early install/migrations
        }
    }
}
