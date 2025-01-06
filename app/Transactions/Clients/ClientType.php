<?php

namespace App\Transactions\Clients;

enum ClientType: string
{
    case Normal = 'normal';
    case Vip = 'vip';
    case Wholesaler = 'wholesaler';
}
