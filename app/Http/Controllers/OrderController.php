<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Client;
use App\Transactions\Orders\OrderStatus;
use App\Transactions\Orders\Receipt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Brick\Math\RoundingMode;

class OrderController extends Controller
{
    public function create(CreateOrderRequest $request): JsonResponse|RedirectResponse
    {
        if (! $quantity = $request->quantity) {
            return $this->getResponse($request);
        }

        $client = $request->user()?->client;
    
        $product = Product::findOrFail($request->id);

        $order = $client->orders()->create([
            'status' => OrderStatus::Cart,
        ]);
       
        
        $item = $this->makeOrderProduct($order, $product);
        $bestPromotion = $this->getBestPromotion($order, $product);
        $item->quantity = $quantity;
        $item->price_final = $item->price_unit->multipliedBy($quantity);
        $type = Client::select('type')->where('id',$order->client_id)->first()->type->value;
        
        if($type != 'wholesale'){
            $item->reduction_amount = $item->price_final->multipliedBy($bestPromotion/100,RoundingMode::DOWN);
        }
        else{
            $item->reduction_amount = $product->price_acquisition->multipliedBy($bestPromotion/100,RoundingMode::DOWN);
        }
      
        $item->save();

        return $this->getResponse($request, $order);
    }

    public function update(UpdateOrderRequest $request, Order $order): JsonResponse|RedirectResponse
    {
        if (! $item = $this->getOrderProduct($request, $order)) {
            return $this->getResponse($request, $order);
        }

        if (! $request->quantity) {
            $item->delete();
        } else {
            $product = Product::findOrFail($request->id);

            $bestPromotion = $this->getBestPromotion($order, $product);
           
            $item->quantity = $request->quantity;
            $item->price_final = $item->price_unit->multipliedBy($request->quantity);
            $type = Client::select('type')->where('id',$order->client_id)->first()->type->value;
            if($type != 'wholesale'){
                $item->reduction_amount = $item->price_final->multipliedBy($bestPromotion/100,RoundingMode::DOWN);
            }
            else{
                $item->reduction_amount = $product->price_acquisition->multipliedBy($bestPromotion/100,RoundingMode::DOWN);
            }

            $item->save();
        }

        if (! $order->products()->count()) {
            $order->forceDelete();

            return $this->getResponse($request);
        }

        return $this->getResponse($request, $order);
    }

    protected function getOrderProduct(UpdateOrderRequest $request, Order $order): ?OrderProduct
    {
        if (! is_null($request->line)) {
            return $order->products->firstWhere('id', $request->line);
        }

        if ($request->quantity) {
            $product = Product::findOrFail($request->id);

            return $this->makeOrderProduct($order, $product);
        }

        return null;
    }

    protected function makeOrderProduct(Order $order, Product $product): OrderProduct
    {
        return $order->products()->make([
            'product_id' => $product->id,
            'price_unit' => $product->price_selling ?? 0,
        ]);
    }

    protected function getBestPromotion(Order $order, Product $product){
        $type = Client::select('type')->where('id',$order->client_id)->first()->type->value;
        $product_category_id = Product::with('categories')->find($product->id);
    
        if($type == 'normal')
            return 0;
        if($type != 'wholesale'){
            $bestPromotion = Promotion::select('percentage')
                ->whereIn('category_id', $product->categories->pluck('id')->toArray())
                ->orwhere('type', $type) 
                ->orderBy('percentage', 'desc')  
                ->first();

        }
        else{
            $bestPromotion = Promotion::select('percentage')
                ->where('type', ClientType::Wholesaler) 
                ->orderBy('percentage', 'desc')  
                ->first();
        }
        return $bestPromotion ? $bestPromotion->percentage : 0;
    }

    protected function getResponse(Request $request, ?Order $order = null): JsonResponse|RedirectResponse
    {
        $receipt = new Receipt($order);

        if ($request->ajax()) {
            return response()->json($receipt);
        }

        return redirect()->to('home');
    }
}
