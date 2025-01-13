<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Transactions\Clients\ClientType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(), 
            'type' => ClientType::Normal->value, // Par défaut, client "Normal"
        ];
    }

    public function normal()
    {
        return $this->state(fn () => ['type' => ClientType::Normal->value]);
    }

    public function vip()
    {
        return $this->state(fn () => ['type' => ClientType::Vip->value]);
    }

    public function wholesaler()
    {
        return $this->state(fn () => ['type' => ClientType::Wholesaler->value]);
    }
}
