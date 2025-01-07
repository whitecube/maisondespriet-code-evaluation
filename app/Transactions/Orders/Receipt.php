<?php

namespace App\Transactions\Orders;

use JsonSerializable;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Support\Collection;

class Receipt implements JsonSerializable
{
    protected ?Order $order;
    public Collection $lines;

    public function __construct(?Order $order = null)
    {
        $this->order = $order;
        $this->lines = $this->getLines();
    }

    protected function getLines(): Collection
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

    public function getDisplayableLines(): Collection
    {
        return $this->lines
            ->filter(fn(ReceiptLine $line) => $line->isDisplayable())
            ->values()
            ->map(fn(ReceiptLine $line) => array_merge($line->getProductAttributes(), [
                'type' => $line->getType(),
                'deletable' => $line->isDeletable(),
                'label' => $line->getLabel(),
                'quantity' => $line->getQuantity(),
                'price' => $line->getDisplayablePrice(),
            ]));
    }

    public function getDisplayableTotal(): string
    {
        return '€&nbsp;1,00'; // TODO.
    }

    public function jsonSerialize(): array
    {
        return [
            'route' => is_null($this->order)
                ? route('order.create')
                : route('order.update', ['order' => $this->order]),
            'lines' => $this->getDisplayableLines(),
            'total' => $this->getDisplayableTotal(),
        ];
    }
}
