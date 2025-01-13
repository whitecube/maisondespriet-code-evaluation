<?php

namespace App\Transactions\Orders\Lines;

use App\Models\Traits\FormatsPrices;
use App\Transactions\Orders\ReceiptLine;
use Brick\Money\Money;
use Illuminate\Support\Collection;

class Reduction implements ReceiptLine
{
    use FormatsPrices;

    protected Money $reduction_amount;

    public function __construct(Collection $products)
    {   
       
        $this->reduction_amount = $products->reduce(function (Money $carry, Product $line) {
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
        return 'Remise';
    }

    public function getQuantity(): ?int
    {
        return null;
    }

    public function getPrice(): Money
    {
        return Money::of(00, 'EUR');
    }
    public function getReductionAmount(): Money
    {
        return $this->reduction_amount;
    }
    public function getDisplayablePrice(): ?string
    {
        return '- '.$this->formatPrice($this->reduction_amount);
    }

    public function getProductAttributes(): array
    {
        return [];
    }
}
