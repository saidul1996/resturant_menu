<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Inertia\Inertia;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $items = Item::with('category')->get();

        return Inertia::render(
            'Menu/Index',
            [
                'items' => $items
            ]
        );
    }
}
