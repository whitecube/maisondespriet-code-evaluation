<?php

namespace App\Transactions\Orders;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Client;
use App\Models\Traits\FormatsPrices;
use Brick\Money\Money;
use Illuminate\Support\Collection;
use JsonSerializable;

class Receipt implements JsonSerializable
{
    use FormatsPrices;

    protected ?Order $order;

    public Collection $products;

    public Collection $detail;
    

    public function __construct(?Order $order = null)
    {
        $this->order = $order;
        $this->products = $this->getProducts();
        $this->detail = $this->getDetail();
        
    }

    protected function getProducts(): Collection
    {
        if (! $this->order) {
            return collect();
        }
        
        return $this->order->products()
            ->with('product')
            ->ordered()
            ->get()
            ->map(fn (OrderProduct $product) => new Lines\Product($product));
    }

    protected function getDetail(): Collection
    {
        if ($this->products->isEmpty()) {
            return collect();
        }
        
        $type = Client::select('type')->where('id',$this->order->client_id)->first()->type->value;
        if($type == 'vip'){
            return collect([
                new Lines\Subtotal($this->products),
                new Lines\Reduction($this->products),
                new Lines\DeliveryFee,
            ]);
        }
        else{
            return collect([
                new Lines\Subtotal($this->products),
                 //new Lines\Reduction($this->products),
                new Lines\DeliveryFee,
            ]);
        }
        
    }

    public function getDisplayableProducts(): array
    {
        return $this->toDisplayableLines($this->products);
    }

    public function getDisplayableDetail(): array
    {
        return $this->toDisplayableLines($this->detail);
    }

    protected function toDisplayableLines(Collection $collection): array
    {
        return $collection
            ->filter(fn (ReceiptLine $line) => $line->isDisplayable())
            ->map(fn (ReceiptLine $line) => array_merge($line->getProductAttributes(), [
                'type' => $line->getType(),
                'deletable' => $line->isDeletable(),
                'label' => $line->getLabel(),
                'quantity' => $line->getQuantity(),
                'price' => $line->getDisplayablePrice(),
            ]))
            ->values()
            ->all();
    }

    public function getTotal(): Money
    {   
        if(!is_null($this->order)){
            $type = Client::select('type')->where('id',$this->order->client_id)->first()->type->value;
            if($type == 'wholesaler'){
                return $this->detail
                 ->reduce(function (Money $carry, ReceiptLine $line) {
                    return $carry->plus($line->getPrice()->plus($line->getMargin()));
                }, Money::zero('EUR'));
            }
        }
        
        return $this->detail
            ->reduce(function (Money $carry, ReceiptLine $line) {
                return $carry->plus($line->getPrice()->minus($line->getReductionAmount()));
            }, Money::zero('EUR'));
       
       
    }

    public function getDisplayableTotal(): string
    {
        return $this->formatPrice($this->getTotal());
    }

    public function jsonSerialize(): array
    {
        return [
            'route' => is_null($this->order)
                ? route('order.create')
                : route('order.update', ['order' => $this->order]),
            'items' => $this->getDisplayableProducts(),
            'detail' => $this->getDisplayableDetail(),
            'total' => $this->getDisplayableTotal(),
        ];
    }
}
