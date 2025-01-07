<?php

namespace App\Transactions\Orders\Lines;

use App\Models\OrderProduct;
use App\Transactions\Orders\ReceiptLine;

class Product implements ReceiptLine
{
    protected OrderProduct $item;

    public function __construct(OrderProduct $item)
    {
        $this->item = $item;
    }

    public function isDisplayable(): bool
    {
        return ($this->item->quantity > 0);
    }

    public function isDeletable(): bool
    {
        return true;
    }

    public function getType(): string
    {
        return 'product';
    }

    public function getLabel(): ?string
    {
        return $this->item->product->name;
    }

    public function getQuantity(): ?int
    {
        return $this->item->quantity;
    }

    public function getDisplayableQuantity(): ?string
    {
        return number_format($this->getQuantity(), 2, ',', '&nbsp;');
    }

    public function getDisplayablePrice(): ?string
    {
        return '€&nbsp;1,00'; // TODO.
    }

    public function getProductAttributes(): array
    {
        return [
            'line' => $this->item->id,
            'product' => $this->item->product->id,
        ];
    }
}