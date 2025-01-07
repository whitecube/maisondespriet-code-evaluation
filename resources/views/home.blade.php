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
                    <dl class="metas">
                        <dt class="metas__term">Client&nbsp;:</dt>
                        <dd class="metas__value">{{ $user->client?->type->label() ?? 'Non défini' }}</dd>
                        <dt class="metas__term">Fournisseur&nbsp;:</dt>
                        <dd class="metas__value">{{ $user->supplier ? 'Oui' : 'Non' }}</dd>
                    </dl>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="button">Se déconnecter</button>
                </form>
            </div>

            <cart :products="{{ json_encode($products) }}" route="{{ $receipt ? route('order.update') : route('order.create') }}" :receipt="{'id':1,'lines':[{'id':154,'line':28,'label':'product','quantity':1,'price':'€ 11,05'}],'total':'€ 11,05'}" />
        </div>
    </body>
</html>
