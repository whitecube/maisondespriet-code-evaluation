<?php

namespace App\Transactions\Orders;

interface ReceiptLine
{
    public function isDisplayable(): bool;
    public function isDeletable(): bool;
    public function getType(): string;
    public function getLabel(): ?string;
    public function getQuantity(): ?int;
    public function getDisplayablePrice(): ?string;
    public function getProductAttributes(): array;
}
