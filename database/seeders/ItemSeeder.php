<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'item_code' => 'RING-001',
                'name' => 'Diamond Engagement Ring',
                'description' => 'Classic solitaire diamond engagement ring with 1 carat center stone',
                'category' => 'Rings',
                'subcategory' => 'Engagement',
                'material' => '18K Gold',
                'gemstone' => 'Diamond',
                'weight' => 3.5,
                'size' => 'Size 6',
                'purity' => 18.0,
                'cost_price' => 2500.00,
                'selling_price' => 3500.00,
                'wholesale_price' => 3000.00,
                'current_stock' => 5,
                'minimum_stock' => 2,
                'maximum_stock' => 10,
                'unit' => 'piece',
                'is_active' => true,
                'is_taxable' => true,
                'tax_rate' => 15.0,
            ],
            [
                'item_code' => 'NECK-001',
                'name' => 'Pearl Necklace',
                'description' => 'Elegant pearl necklace with 16-inch length',
                'category' => 'Necklaces',
                'subcategory' => 'Pearl',
                'material' => '14K Gold',
                'gemstone' => 'Pearl',
                'weight' => 8.2,
                'size' => '16 inches',
                'purity' => 14.0,
                'cost_price' => 800.00,
                'selling_price' => 1200.00,
                'wholesale_price' => 1000.00,
                'current_stock' => 8,
                'minimum_stock' => 3,
                'maximum_stock' => 15,
                'unit' => 'piece',
                'is_active' => true,
                'is_taxable' => true,
                'tax_rate' => 15.0,
            ],
            [
                'item_code' => 'EARR-001',
                'name' => 'Gold Hoop Earrings',
                'description' => 'Classic gold hoop earrings with diamond accents',
                'category' => 'Earrings',
                'subcategory' => 'Hoops',
                'material' => '18K Gold',
                'gemstone' => 'Diamond',
                'weight' => 2.1,
                'size' => 'Medium',
                'purity' => 18.0,
                'cost_price' => 450.00,
                'selling_price' => 650.00,
                'wholesale_price' => 550.00,
                'current_stock' => 12,
                'minimum_stock' => 5,
                'maximum_stock' => 20,
                'unit' => 'pair',
                'is_active' => true,
                'is_taxable' => true,
                'tax_rate' => 15.0,
            ],
            [
                'item_code' => 'BRAC-001',
                'name' => 'Silver Charm Bracelet',
                'description' => 'Sterling silver charm bracelet with multiple charms',
                'category' => 'Bracelets',
                'subcategory' => 'Charm',
                'material' => 'Sterling Silver',
                'gemstone' => 'None',
                'weight' => 15.5,
                'size' => '7 inches',
                'purity' => 92.5,
                'cost_price' => 120.00,
                'selling_price' => 180.00,
                'wholesale_price' => 150.00,
                'current_stock' => 20,
                'minimum_stock' => 8,
                'maximum_stock' => 30,
                'unit' => 'piece',
                'is_active' => true,
                'is_taxable' => true,
                'tax_rate' => 15.0,
            ],
            [
                'item_code' => 'WATCH-001',
                'name' => 'Luxury Watch',
                'description' => 'Premium luxury watch with leather strap',
                'category' => 'Watches',
                'subcategory' => 'Luxury',
                'material' => 'Stainless Steel',
                'gemstone' => 'None',
                'weight' => 85.0,
                'size' => '42mm',
                'purity' => 0.0,
                'cost_price' => 1200.00,
                'selling_price' => 1800.00,
                'wholesale_price' => 1500.00,
                'current_stock' => 3,
                'minimum_stock' => 1,
                'maximum_stock' => 5,
                'unit' => 'piece',
                'is_active' => true,
                'is_taxable' => true,
                'tax_rate' => 15.0,
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }

        $this->command->info('Items seeded successfully!');
    }
}
