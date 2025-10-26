<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TastingRound;
use App\Models\Category;
use App\Models\Snack;
use App\Models\RoundSnack;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user (Helia Haghighi)
        $admin = User::firstOrCreate(
            ['email' => 'helia.haghighi@milele.com'],
            [
                'name' => 'Helia Haghighi',
                'role' => 'admin',
            ]
        );

        // Create authorized employee users (participants)
        $authorizedEmails = [
            'anwar.valiyaveettil@milele.com',
            'areej.mukhtar@milele.com', 
            'evita.upeniece@milele.com',
            'somanath.kumar@milele.com',
            'shahid.nawaz@milele.com',
            'fouad.mohamed@milele.com',
            'farkhanda.naz@milele.com',
            'feroz.riaz@milele.com'
        ];

        foreach ($authorizedEmails as $email) {
            User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $this->generateNameFromEmail($email),
                    'role' => 'participant',
                ]
            );
        }

        // // Create categories
        // $chips = Category::create([
        //     'name' => 'Chips & Crisps',
        //     'description' => 'Various types of potato and corn chips'
        // ]);

        // $beverages = Category::create([
        //     'name' => 'Beverages',
        //     'description' => 'Drinks and beverages'
        // ]);

        // $chocolate = Category::create([
        //     'name' => 'Chocolate & Confectionery',
        //     'description' => 'Chocolate bars and sweet snacks'
        // ]);

        // $nuts = Category::create([
        //     'name' => 'Nuts & Seeds',
        //     'description' => 'Roasted nuts and seed mixes'
        // ]);

        // // Create snacks
        // $snacks = [
        //     [
        //         'category_id' => $chips->id,
        //         'name' => 'Classic Sea Salt',
        //         'brand' => 'Milele Crisps',
        //         'description' => 'Thinly sliced potatoes with premium sea salt'
        //     ],
        //     [
        //         'category_id' => $chips->id,
        //         'name' => 'Spicy Barbecue',
        //         'brand' => 'Milele Crisps',
        //         'description' => 'Smoky barbecue flavor with a hint of spice'
        //     ],
        //     [
        //         'category_id' => $chocolate->id,
        //         'name' => 'Dark Chocolate Bar',
        //         'brand' => 'Milele Delights',
        //         'description' => '70% dark chocolate with cocoa nibs'
        //     ],
        //     [
        //         'category_id' => $chocolate->id,
        //         'name' => 'Milk Chocolate Bites',
        //         'brand' => 'Milele Delights',
        //         'description' => 'Creamy milk chocolate in bite-sized pieces'
        //     ],
        //     [
        //         'category_id' => $nuts->id,
        //         'name' => 'Honey Roasted Almonds',
        //         'brand' => 'Milele Nuts',
        //         'description' => 'California almonds roasted with pure honey'
        //     ],
        //     [
        //         'category_id' => $beverages->id,
        //         'name' => 'Tropical Fruit Drink',
        //         'brand' => 'Milele Beverages',
        //         'description' => 'Refreshing blend of tropical fruits'
        //     ]
        // ];

        // foreach ($snacks as $snackData) {
        //     Snack::create($snackData);
        // }

        // Create tasting round
        // $round = TastingRound::create([
        //     'name' => 'Q4 2024 Product Testing',
        //     'description' => 'Evaluation of new snack products for Q4 2024 launch',
        //     'is_active' => true,
        //     'created_by' => $admin->id,
        // ]);

        // Add snacks to round with sequence
        // $sequence = 1;
        // foreach (Snack::all() as $snack) {
        //     RoundSnack::create([
        //         'tasting_round_id' => $round->id,
        //         'snack_id' => $snack->id,
        //         'sequence_order' => $sequence++
        //     ]);
        // }

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('👑 Admin credentials:');
        $this->command->info('   Email: admin@milele.com');
        $this->command->info('');
        $this->command->info('👥 Authorized employee emails:');
        foreach ($authorizedEmails as $email) {
            $this->command->info("   - {$email}");
        }
    }

    private function generateNameFromEmail($email)
    {
        $username = strstr($email, '@', true);
        $nameParts = explode('.', $username);
        $name = implode(' ', array_map('ucfirst', $nameParts));
        return $name;
    }
}