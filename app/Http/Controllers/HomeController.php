<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function show(): View
    {
        $user = auth()->user();

        return view('home', [
            'user' => $user
        ]);
    }
}
