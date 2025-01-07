<?php

namespace App\Transactions\Orders\Lines;

use App\Transactions\Orders\ReceiptLine;

class DeliveryFee implements ReceiptLine
{
    public function __construct()
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
        return 'Livraison';
    }

    public function getQuantity(): ?int
    {
        return null;
    }

    public function getDisplayablePrice(): ?string
    {
        return '€&nbsp;0,00'; // TODO.
    }

    public function getProductAttributes(): array
    {
        return [];
    }
}