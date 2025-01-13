<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Transactions\Clients\ClientType;
use App\Models\Category;
use App\Models\Promotion;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insérer promotions sur les catégories
        Promotion::create([
            'percentage' => 5.00,
            'category_id' => Category::where('name', 'Surgelés')->first()->id, 
            'type' => null 
        ]);

        Promotion::create([
            'percentage' => 15.00,
            'category_id' => Category::where('name', 'Promotions')->first()->id, 
            'type' => null 
        ]);

        // Insérer promotions sur les type de clients
        Promotion::create([
            'percentage' => 30.00,
            'category_id' => null, 
            'type' => ClientType::Wholesaler 
        ]);
        
        Promotion::create([
            'percentage' => 10.00,
            'category_id' => null, 
            'type' =>  ClientType::Vip, 
        ]);
    }
}
