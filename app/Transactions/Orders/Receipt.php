<?php

namespace App\Transactions\Orders;

use JsonSerializable;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Traits\FormatsPrices;
use App\Transactions\Orders\Lines\Fake;
use Illuminate\Support\Collection;
use Brick\Money\Money;

class Receipt implements JsonSerializable
{
    use FormatsPrices;
    
    protected ?Order $order;
    public Collection $products;
    public Collection $aggregates;

    public function __construct(?Order $order = null)
    {
        $this->order = $order;
        $this->products = $this->getProducts();
        $this->aggregates = $this->getAggregates();
    }

    protected function getProducts(): Collection
    {
        if(! $this->order) {
            return collect();
        }

        return $this->order->products()
            ->with('product')
            ->ordered()
            ->get()
            ->map(fn(OrderProduct $product) => new Lines\Product($product));
    }

    protected function getAggregates(): Collection
    {
        if($this->products->isEmpty()) {
            return collect();
        }

        return collect([
            new Lines\Subtotal($this->products),
            new Lines\DeliveryFee(),
        ]);
    }

    public function getDisplayableProducts(): array
    {
        return $this->toDisplayableLines($this->products);
    }

    public function getDisplayableAggregates(): array
    {
        return $this->toDisplayableLines($this->aggregates);
    }

    protected function toDisplayableLines(Collection $collection): array
    {
        return $collection
            ->filter(fn(ReceiptLine $line) => $line->isDisplayable())
            ->map(fn(ReceiptLine $line) => array_merge($line->getProductAttributes(), [
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
        return $this->aggregates
            ->reduce(function (Money $carry, ReceiptLine $aggregate) {
                return $carry->plus($aggregate->getPrice());
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
            'aggregates' => $this->getDisplayableAggregates(),
            'total' => $this->getDisplayableTotal(),
        ];
    }
}
