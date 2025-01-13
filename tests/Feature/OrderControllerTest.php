<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Client;
use App\Models\OrderProduct;
use App\Models\Order;
use App\Models\Product;
use Brick\Money\Money;
use App\Models\Category;
use App\Models\Promotion;
use App\Transactions\Clients\ClientType;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that no promotions are applied for normal clients.
     */
    public function test_no_promotions_for_normal_clients()
    {
       
        $client = Client::factory()->create([
            'type' => ClientType::Normal->value, 
        ]);

  
        $category = Category::factory()->create(['name' => 'Electronics']);
        $product = Product::factory()->create([
            'name' => 'Smartphone',
            'ref' => 'SMA001',
            'main_category_id' => $category->id,
            'price_selling' => 1000.00,
            'price_acquisition' => 500
        ]);

      
        Promotion::create([
            'category_id' => $category->id,
            'type' => ClientType::Normal->value, 
            'percentage' => 20,
        ]);

       
        $response = $this->actingAs($client->user)->post('/order', [
            'id' => $product->id,
            'quantity' => 2,
        ]);

    
        
        $order = Order::first();
        $item = $order->products->first();
      
        $this->assertEquals(Money::of(0, 'EUR'), $item->reduction_amount);
    }

    public function test_promotions_for_vip_clients()
    {
        $client = Client::factory()->create([
            'type' => ClientType::Vip->value, 
        ]);

        $category = Category::factory()->create(['name' => 'Electronics']);
        $product = Product::factory()->create([
            'name' => 'Smartphone',
            'ref' => 'SMA001',
            'main_category_id' => $category->id,
            'price_selling' => 1000,
            'price_acquisition' => 500
        ]);
        $percentage = 10;
        Promotion::create([
            'category_id' => NULL,
            'type' => ClientType::Vip->value, // Promotion applicable uniquement aux clients VIP
            'percentage' =>  $percentage,
        ]);
        $promotions = Promotion::all();
        $quantity = 2;
        $response = $this->actingAs($client->user)->post('/order', [
            'id' => $product->id,
            'user' => $client,
            'quantity' => $quantity,
        ]);
       
        $orderProduct = OrderProduct::first();
    
        $this->assertEquals($product->price_selling->multipliedBy(2)->dividedBy(100/$percentage), $orderProduct->reduction_amount);
    }


    public function test_promotions_for_frozen_category()
    {
        $client = Client::factory()->create([
            'type' => ClientType::Vip->value, 
        ]);

        $category = Category::factory()->create(['name' => 'Surgelés']);
        $product = Product::factory()->create([
            'name' => 'Smartphone froid',
            'ref' => 'SMA001',
            'main_category_id' => $category->id,
            'price_selling' => 1000,
            'price_acquisition' => 500
        ]);
        $percentage = 5;
        Promotion::create([
            'category_id' => $category->id, // Promotion applicable uniquement sur la catégorie surgelés
            'type' => ClientType::Vip->value, 
            'percentage' => $percentage,
        ]);
        $promotions = Promotion::all();
        $quantity = 2;
        $response = $this->actingAs($client->user)->post('/order', [
            'id' => $product->id,
            'user' => $client,
            'quantity' => $quantity,
        ]);
  
        $orderProduct = OrderProduct::first();
      
        $this->assertEquals($product->price_selling->multipliedBy(2)->dividedBy(100/$percentage), $orderProduct->reduction_amount);
    }

    public function test_no_promotions_for_frozen_category_and_wholesaler()
    {
        $client = Client::factory()->create([
            'type' => ClientType::Wholesaler->value, 
        ]);

        $category = Category::factory()->create(['name' => 'Surgelés']);
        $product = Product::factory()->create([
            'name' => 'Smartphone froid',
            'ref' => 'SMA001',
            'main_category_id' => $category->id,
            'price_selling' => 1000,
            'price_acquisition' => 500
        ]);
        
        Promotion::create([
            'category_id' => $category->id, // Promotion applicable uniquement sur la catégorie surgelés
            'type' => null, 
            'percentage' => 5,
        ]);
        $promotions = Promotion::all();
        
        $response = $this->actingAs($client->user)->post('/order', [
            'id' => $product->id,
            'user' => $client,
            'quantity' => 2,
        ]);
  
        $orderProduct = OrderProduct::first();
      
        $this->assertEquals(Money::of(0, 'EUR'), $orderProduct->reduction_amount);
    }



    public function test_promotions_for_wholesaler()
    {
        $client = Client::factory()->create([
            'type' => ClientType::Wholesaler->value, 
        ]);

        $category = Category::factory()->create(['name' => 'Surgelés']);

        $product = Product::factory()->create([
            'name' => 'Smartphone froid',
            'ref' => 'SMA001',
            'main_category_id' => $category->id,
            'price_selling' => 1000,
            'price_acquisition' => 500
        ]);
        
        $percentage = 20;
        Promotion::create([
            'category_id' => $category->id, // Promotion applicable uniquement sur la catégorie surgelés
            'type' => ClientType::Wholesaler->value, 
            'percentage' => $percentage,
        ]);

        $promotions = Promotion::all();
        $quantity = 1;
        $response = $this->actingAs($client->user)->post('/order', [
            'id' => $product->id,
            'user' => $client,
            'quantity' => $quantity,
        ]);
  
        $orderProduct = OrderProduct::first();
      //  dd($product);
        //dd($orderProduct);

        $this->assertEquals($product->price_acquisition->multipliedBy($quantity)->dividedBy(100/$percentage), $orderProduct->reduction_amount);
    }

}
