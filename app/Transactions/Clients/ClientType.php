<?php

namespace App\Transactions\Clients;

enum ClientType: string
{
    case Normal = 'normal';
    case Vip = 'vip';
    case Wholesaler = 'wholesaler';

    public function label(): string
    {
        return match ($this) {
            static::Normal => 'Normal',
            static::Vip => 'VIP',
            static::Wholesaler => 'Grossiste',
        };
    }
}
