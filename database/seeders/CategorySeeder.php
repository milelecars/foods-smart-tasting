<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Flavored Wellness Water',
                'description' => 'citrus, berry, or cucumber-mint infused; vitamin water options for daily hydration.'
            ],
            [
                'name' => 'Healthy Snack Bars',
                'description' => 'fruit & oat bars, cereal bars for on-the-go snacking.'
            ],
            [
                'name' => 'Protein Bars',
                'description' => '10–12 g protein, entry-Level and affordable options for active consumers.'
            ],
            [
                'name' => 'Savory Healthy Snacks',
                'description' => 'baked chips, veggie puffs, popcorn, and crackers in regional flavors.'
            ],
            [
                'name' => 'Healthy Breakfast Range (Granola, Oats, Muesli)',
                'description' => 'granola, muesli, and instant oats for quick morning meals.'
            ],
            [
                'name' => 'Kids\' Everyday Nutrition Range',
                'description' => 'fruit pouches, mini bars, flavored milk; affordable and nutritious school snacks.'
            ],
            [
                'name' => 'Non-alcoholic Sparkling Drinks & Botanical Spritzers',
                'description' => 'sparkling grape/apple/peach + botanical spritzers (ashwagandha/elderflower).'
            ],
            [
                'name' => 'Vitamin / Fruit & Herb-Infused Teas',
                'description' => 'Elevated, guilt-free celebratory drinks of citrus-mint, strawberry-cucumber.'
            ],
            [
                'name' => 'Premium Chocolates',
                'description' => 'dark 70% (almond/sea salt) + smooth milk (hazelnut/strawberry crisp).'
            ],
            [
                'name' => 'Date-Based Bars',
                'description' => 'Natural , no refined sugar snack bars made with dates.'
            ],
            [
                'name' => 'Fruit & Oat Bars',
                'description' => 'Natural , no refined sugar snack bars made with dried fruits.'
            ],
            [
                'name' => 'Limited Edition / Seasonal Collab Products',
                'description' => 'Special edition and seasonal collaboration products'
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['name' => $categoryData['name']],
                [
                    'description' => $categoryData['description'],
                    'parent_id' => null,
                ]
            );
        }

        $this->command->info('✅ ' . count($categories) . ' categories seeded successfully!');
    }
}
