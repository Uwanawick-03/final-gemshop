<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Models\JobIssue;
use App\Models\WorkshopAdjustment;
use App\Models\FinishedGoodTransfer;
use App\Models\CraftsmanReturn;
use App\Models\Mtc;
use App\Models\TourGuide;
use App\Models\Craftsman;
use App\Models\SalesAssistant;
use App\Models\SalesExecutive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AboutController extends Controller
{
    public function index()
    {
        // Get system statistics
        $systemStats = $this->getSystemStatistics();
        
        // Get system information
        $systemInfo = $this->getSystemInformation();
        
        // Get recent activity
        $recentActivity = $this->getRecentActivity();
        
        // Get database information
        $databaseInfo = $this->getDatabaseInformation();
        
        // Get feature modules
        $featureModules = $this->getFeatureModules();
        
        // Get system health
        $systemHealth = $this->getSystemHealth();

        return view('about', compact(
            'systemStats',
            'systemInfo',
            'recentActivity',
            'databaseInfo',
            'featureModules',
            'systemHealth'
        ));
    }

    public function systemInfo()
    {
        $systemInfo = $this->getSystemInformation();
        $databaseInfo = $this->getDatabaseInformation();
        $systemHealth = $this->getSystemHealth();

        return response()->json([
            'system' => $systemInfo,
            'database' => $databaseInfo,
            'health' => $systemHealth
        ]);
    }

    private function getSystemStatistics()
    {
        try {
            return [
                'users' => User::count(),
                'items' => Item::count(),
                'customers' => Customer::count(),
                'suppliers' => Supplier::count(),
                'invoices' => Invoice::count(),
                'purchase_orders' => PurchaseOrder::count(),
                'job_issues' => JobIssue::count(),
                'workshop_adjustments' => WorkshopAdjustment::count(),
                'finished_good_transfers' => FinishedGoodTransfer::count(),
                'craftsman_returns' => CraftsmanReturn::count(),
                'mtcs' => Mtc::count(),
                'tour_guides' => TourGuide::count(),
                'craftsmen' => Craftsman::count(),
                'sales_assistants' => SalesAssistant::count(),
                'sales_executives' => SalesExecutive::count(),
            ];
        } catch (\Exception $e) {
            return [
                'users' => 0,
                'items' => 0,
                'customers' => 0,
                'suppliers' => 0,
                'invoices' => 0,
                'purchase_orders' => 0,
                'job_issues' => 0,
                'workshop_adjustments' => 0,
                'finished_good_transfers' => 0,
                'craftsman_returns' => 0,
                'mtcs' => 0,
                'tour_guides' => 0,
                'craftsmen' => 0,
                'sales_assistants' => 0,
                'sales_executives' => 0,
            ];
        }
    }

    private function getSystemInformation()
    {
        return [
            'app_name' => config('app.name'),
            'app_version' => '1.0.0',
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug'),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'server_os' => PHP_OS,
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'database_driver' => config('database.default'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_driver' => config('queue.default'),
        ];
    }

    private function getRecentActivity()
    {
        try {
            $activities = collect();
            
            // Get recent users
            $recentUsers = User::latest()->limit(3)->get();
            foreach ($recentUsers as $user) {
                $activities->push([
                    'type' => 'user',
                    'icon' => 'fas fa-user',
                    'color' => 'primary',
                    'title' => 'New User Registered',
                    'description' => $user->name . ' joined the system',
                    'time' => $user->created_at->diffForHumans()
                ]);
            }

            // Get recent items
            $recentItems = Item::latest()->limit(3)->get();
            foreach ($recentItems as $item) {
                $activities->push([
                    'type' => 'item',
                    'icon' => 'fas fa-gem',
                    'color' => 'warning',
                    'title' => 'New Item Added',
                    'description' => $item->name . ' added to inventory',
                    'time' => $item->created_at->diffForHumans()
                ]);
            }

            // Get recent customers
            $recentCustomers = Customer::latest()->limit(3)->get();
            foreach ($recentCustomers as $customer) {
                $activities->push([
                    'type' => 'customer',
                    'icon' => 'fas fa-user-friends',
                    'color' => 'success',
                    'title' => 'New Customer Added',
                    'description' => $customer->full_name . ' registered',
                    'time' => $customer->created_at->diffForHumans()
                ]);
            }

            return $activities->sortByDesc('time')->take(10);
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function getDatabaseInformation()
    {
        try {
            $tables = DB::select('SHOW TABLES');
            $tableNames = array_map(function($table) {
                return array_values((array)$table)[0];
            }, $tables);

            $tableInfo = [];
            foreach ($tableNames as $tableName) {
                $count = DB::table($tableName)->count();
                $tableInfo[] = [
                    'name' => $tableName,
                    'count' => $count,
                    'size' => $this->getTableSize($tableName)
                ];
            }

            return [
                'total_tables' => count($tableNames),
                'tables' => $tableInfo,
                'total_records' => array_sum(array_column($tableInfo, 'count')),
                'database_name' => config('database.connections.' . config('database.default') . '.database'),
                'database_driver' => config('database.default')
            ];
        } catch (\Exception $e) {
            return [
                'total_tables' => 0,
                'tables' => [],
                'total_records' => 0,
                'database_name' => 'Unknown',
                'database_driver' => 'Unknown'
            ];
        }
    }

    private function getTableSize($tableName)
    {
        try {
            $result = DB::select("SELECT 
                ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb 
                FROM information_schema.TABLES 
                WHERE table_schema = DATABASE() 
                AND table_name = ?", [$tableName]);
            
            return $result[0]->size_mb ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getFeatureModules()
    {
        return [
            [
                'name' => 'Inventory Management',
                'icon' => 'fas fa-boxes',
                'color' => 'primary',
                'description' => 'Complete jewelry inventory tracking, stock management, and item categorization',
                'features' => ['Item Management', 'Stock Tracking', 'Category Management', 'Supplier Management']
            ],
            [
                'name' => 'Sales Management',
                'icon' => 'fas fa-shopping-cart',
                'color' => 'success',
                'description' => 'Sales orders, invoices, customer management, and payment tracking',
                'features' => ['Sales Orders', 'Invoices', 'Customer Management', 'Payment Tracking']
            ],
            [
                'name' => 'Purchase Management',
                'icon' => 'fas fa-truck',
                'color' => 'info',
                'description' => 'Purchase orders, goods received notes, and supplier management',
                'features' => ['Purchase Orders', 'GRN Management', 'Supplier Management', 'Order Tracking']
            ],
            [
                'name' => 'Workshop Management',
                'icon' => 'fas fa-tools',
                'color' => 'warning',
                'description' => 'Job issues, workshop adjustments, finished good transfers, and craftsman returns',
                'features' => ['Job Issues', 'Workshop Adjustments', 'Finished Good Transfers', 'Craftsman Returns']
            ],
            [
                'name' => 'MTC Management',
                'icon' => 'fas fa-certificate',
                'color' => 'secondary',
                'description' => 'Material Transfer Certificates for jewelry items and customer tracking',
                'features' => ['MTC Creation', 'Status Tracking', 'Expiry Management', 'PDF Export']
            ],
            [
                'name' => 'Guide Management',
                'icon' => 'fas fa-users',
                'color' => 'dark',
                'description' => 'Tour guide management, performance tracking, and compliance monitoring',
                'features' => ['Guide Registration', 'Performance Tracking', 'License Management', 'Compliance Reports']
            ],
            [
                'name' => 'Reports & Analytics',
                'icon' => 'fas fa-chart-line',
                'color' => 'danger',
                'description' => 'Comprehensive reporting system with sales, inventory, and workshop analytics',
                'features' => ['Sales Reports', 'Inventory Reports', 'Workshop Reports', 'Export Options']
            ],
            [
                'name' => 'System Management',
                'icon' => 'fas fa-cogs',
                'color' => 'primary',
                'description' => 'User management, permissions, roles, and system configuration',
                'features' => ['User Management', 'Role & Permissions', 'System Settings', 'Security']
            ]
        ];
    }

    private function getSystemHealth()
    {
        $health = [
            'overall' => 'good',
            'checks' => []
        ];

        try {
            // Database connectivity check
            DB::connection()->getPdo();
            $health['checks']['database'] = [
                'status' => 'good',
                'message' => 'Database connection is healthy'
            ];
        } catch (\Exception $e) {
            $health['checks']['database'] = [
                'status' => 'error',
                'message' => 'Database connection failed'
            ];
            $health['overall'] = 'error';
        }

        try {
            // Cache check
            cache()->put('health_check', 'ok', 60);
            $cacheTest = cache()->get('health_check');
            if ($cacheTest === 'ok') {
                $health['checks']['cache'] = [
                    'status' => 'good',
                    'message' => 'Cache system is working'
                ];
            } else {
                throw new \Exception('Cache test failed');
            }
        } catch (\Exception $e) {
            $health['checks']['cache'] = [
                'status' => 'warning',
                'message' => 'Cache system has issues'
            ];
        }

        try {
            // Storage check
            $storageTest = storage_path();
            if (is_writable($storageTest)) {
                $health['checks']['storage'] = [
                    'status' => 'good',
                    'message' => 'Storage is writable'
                ];
            } else {
                throw new \Exception('Storage not writable');
            }
        } catch (\Exception $e) {
            $health['checks']['storage'] = [
                'status' => 'error',
                'message' => 'Storage is not writable'
            ];
            $health['overall'] = 'error';
        }

        try {
            // Session check
            session(['health_check' => 'ok']);
            $sessionTest = session('health_check');
            if ($sessionTest === 'ok') {
                $health['checks']['session'] = [
                    'status' => 'good',
                    'message' => 'Session system is working'
                ];
            } else {
                throw new \Exception('Session test failed');
            }
        } catch (\Exception $e) {
            $health['checks']['session'] = [
                'status' => 'warning',
                'message' => 'Session system has issues'
            ];
        }

        return $health;
    }
}
