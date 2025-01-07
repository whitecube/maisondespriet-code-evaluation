<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = $this->createCategories();

        Product::create([
            'ref' => '.LEGAPO',
            'main_category_id' => $categories['vege']->id,
            'name' => 'Asperge verte pointe',
        ])->categories()->attach([$categories['vege']->id, $categories['discounts']->id]);

        Product::create([
            'ref' => '.LEGAUL',
            'main_category_id' => $categories['vege']->id,
            'name' => 'Aubergine allongée',
        ])->categories()->attach([$categories['vege']->id]);

        Product::create([
            'ref' => '.ANPO',
            'main_category_id' => $categories['frozen']->id,
            'name' => 'Anneaux de poireau',
        ])->categories()->attach([$categories['frozen']->id, $categories['vege']->id]);

        Product::create([
            'ref' => '.97186',
            'main_category_id' => $categories['frozen']->id,
            'name' => 'Brunoise de légumes',
        ])->categories()->attach([$categories['frozen']->id, $categories['vege']->id, $categories['discounts']->id]);

        Product::create([
            'ref' => '.CFDB6',
            'main_category_id' => $categories['deserts']->id,
            'name' => 'Coupe fruits des bois',
        ])->categories()->attach([$categories['deserts']->id, $categories['frozen']->id]);

        Product::create([
            'ref' => '.53121',
            'main_category_id' => $categories['deserts']->id,
            'name' => 'Coupe crème glacée brésilienne',
        ])->categories()->attach([$categories['deserts']->id, $categories['frozen']->id]);

        Product::create([
            'ref' => '.YAGREC',
            'main_category_id' => $categories['deserts']->id,
            'name' => 'Yaourt grec',
        ])->categories()->attach([$categories['deserts']->id]);

        Product::create([
            'ref' => '.MOJ',
            'main_category_id' => $categories['drinks']->id,
            'name' => 'Mojito sans alcool',
        ])->categories()->attach([$categories['drinks']->id]);

        Product::create([
            'ref' => '.FOGC',
            'main_category_id' => $categories['drinks']->id,
            'name' => 'Fanta Orange',
        ])->categories()->attach([$categories['drinks']->id]);

        Product::create([
            'ref' => '.TRCO',
            'main_category_id' => $categories['drinks']->id,
            'name' => 'Tropico Original',
        ])->categories()->attach([$categories['drinks']->id, $categories['discounts']->id]);
    }

    protected function createCategories(): array
    {
        return [
            'vege' => Category::create(['name' => 'Fruits & légumes']),
            'deserts' => Category::create(['name' => 'Desserts']),
            'frozen' => Category::create(['name' => 'Surgelés']),
            'drinks' => Category::create(['name' => 'Boissons']),
            'discounts' => Category::create(['name' => 'Promotions']),
        ];
    }
}
