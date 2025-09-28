<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupProductionData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:production {--force : Force the operation without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up test data and setup production environment with admin users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Setting up Production Environment for Gem Shop Management System');
        $this->info('================================================================');
        $this->newLine();

        if (!$this->option('force')) {
            if (!$this->confirm('⚠️  This will DELETE ALL existing data and create fresh admin users. Continue?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->newLine();
        $this->info('🧹 Starting cleanup and setup process...');
        $this->newLine();

        try {
            // Run the production data seeder
            $this->call('db:seed', [
                '--class' => 'ProductionDataSeeder',
                '--force' => true,
            ]);

            $this->newLine();
            $this->info('✅ Production environment setup completed successfully!');
            $this->newLine();
            
            $this->info('🔐 Administrative Users Created:');
            $this->line('   📧 Admin: admin@gemshop.com');
            $this->line('   🔑 Password: Admin@2024!');
            $this->newLine();
            $this->line('   📧 Manager: manager@gemshop.com');
            $this->line('   🔑 Password: Manager@2024!');
            $this->newLine();
            
            $this->warn('⚠️  IMPORTANT: Please change these passwords after first login!');
            $this->newLine();
            
            $this->info('🎉 Your Gem Shop Management System is ready for production use!');
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Error during setup: ' . $e->getMessage());
            return 1;
        }
    }
}
