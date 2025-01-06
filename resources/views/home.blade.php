<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        @vite(['resources/css/reset.css', 'resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div id="app">
            <div class="user">
                <div>
                    <h1 class="title">{{ $user->name }}</h1>
                    <p>Type de client : {{ $user->client?->type->label() ?? 'Non défini' }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="button">Se déconnecter</button>
                </form>
            </div>

            <cart :products="[
                {id: 1, name: 'Produit 1', category: 'Surgelés' },
                {id: 2, name: 'Produit 2', category: 'Surgelés' },
                {id: 3, name: 'Produit 3', category: 'Surgelés' },
                {id: 4, name: 'Produit 4', category: 'Surgelés' },
                {id: 5, name: 'Produit 5', category: 'Surgelés' },
                {id: 6, name: 'Produit 6', category: 'Surgelés' },
                {id: 7, name: 'Produit 7', category: 'Surgelés' },
                {id: 8, name: 'Produit 8', category: 'Surgelés' },
                {id: 9, name: 'Produit 9', category: 'Surgelés' },
            ]" />
        </div>
    </body>
</html>
