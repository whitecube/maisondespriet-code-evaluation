<?php

namespace App\Transactions\Orders\Lines;

use App\Models\Traits\FormatsPrices;
use App\Transactions\Orders\ReceiptLine;
use Brick\Money\Money;
use Illuminate\Support\Collection;

class Subtotal implements ReceiptLine
{
    use FormatsPrices;

    protected Money $total;
    protected Money $margin;

    public function __construct(Collection $products)
    {
        $this->total = $products->reduce(function (Money $carry, Product $line) {
            return $carry->plus($line->getPrice());
        }, Money::zero('EUR'));
        
        $this->margin = $products->reduce(function (Money $carry, Product $line) {
            return $carry->plus($line->getReductionAmount());
        }, Money::zero('EUR'));
        
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
        return 'detail';
    }

    public function getLabel(): ?string
    {
        return 'Sous-total';
    }

    public function getQuantity(): ?int
    {
        return null;
    }

    public function getPrice(): Money
    {
        return $this->total;
    }
    public function getReductionAmount(): Money
    {
        return Money::of(00, 'EUR');
    }

    public function getMargin(): Money
    {
        return $this->margin;
    }
    public function getDisplayablePrice(): ?string
    {
        return $this->formatPrice($this->total);
    }

    public function getProductAttributes(): array
    {
        return [];
    }
}
