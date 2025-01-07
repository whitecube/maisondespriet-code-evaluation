<?php

namespace App\Transactions\Orders\Lines;

use App\Models\OrderProduct;
use App\Transactions\Orders\ReceiptLine;
use Illuminate\Support\Collection;

class Subtotal implements ReceiptLine
{
    public function __construct(Collection $products)
    {
        // TODO.
    }

    public function isDisplayable(): bool
    {
        return true;
    }

    public function isDeletable(): bool
    {
        return false;
    }

    public function getType(): string
    {
        return 'aggregate';
    }

    public function getLabel(): ?string
    {
        return 'Sous-total';
    }

    public function getQuantity(): ?int
    {
        return null;
    }

    public function getDisplayablePrice(): ?string
    {
        return '€&nbsp;1,00'; // TODO.
    }

    public function getProductAttributes(): array
    {
        return [];
    }
}