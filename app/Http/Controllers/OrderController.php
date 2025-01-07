<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Transactions\Orders\OrderStatus;
use App\Transactions\Orders\Receipt;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        if(! ($client = $request->user()?->client)) {
            return abort(400);
        }

        $data = $request->validate([
            'id' => ['required','exists:products'],
            'quantity' => ['required','numeric','min:0'],
        ]);

        if(! ($quantity = intval($data['quantity']))) {
            return $this->getResponse($request);
        }

        $product = Product::findOrFail($data['id']);

        $order = $client->orders()->create([
            'status' => OrderStatus::Cart,
        ]);

        $item = $this->makeOrderProduct($order, $product);
        $item->quantity = $quantity;
        $item->price_final = $item->price_unit->multipliedBy($quantity);
        $item->save();

        return $this->getResponse($request, $order);
    }

    public function update(Request $request, Order $order)
    {
        if(! ($client = $request->user()?->client)) {
            return abort(400);
        }

        if($order->client_id !== $client->id) {
            return abort(404);
        }

        $order->setRelation('client', $client);

        $data = $request->validate([
            'id' => ['required','exists:products'],
            'line' => ['nullable', Rule::in($order->products->pluck('id')->all())],
            'quantity' => ['required','numeric','min:0'],
        ]);

        $product = Product::findOrFail($data['id']);
        $quantity = intval($data['quantity']);

        if($data['line'] ?? null) {
            $item = $order->products->firstWhere('id', $data['line']);
        } else if ($quantity) {
            $item = $this->makeOrderProduct($order, $product, $quantity);
        } else {
            return $this->getResponse($request, $order);
        }

        if(! $quantity) {
            $item->delete();
        } else {
            $item->quantity = $quantity;
            $item->price_final = $item->price_unit->multipliedBy($quantity);
            $item->save();
        }

        if(! $order->products()->count()) {
            $order->forceDelete();
            return $this->getResponse($request);
        }

        return $this->getResponse($request,$order);
    }

    protected function makeOrderProduct(Order $order, Product $product): OrderProduct
    {
        return $order->products()->make([
            'product_id' => $product->id,
            'price_unit' => $product->price_selling ?? 0,
        ]);
    }

    protected function getResponse(Request $request, ?Order $order = null)
    {
        $receipt = new Receipt($order);

        if($request->ajax()) {
            return response()->json($receipt);
        }

        return redirect()->to('home');
    }
}
