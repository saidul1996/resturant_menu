<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::with('childrens.childrens.childrens')->whereNull('parent_id')->get();
        $items = Item::all();

        return Inertia::render(
            'Discount/Create',
            [
                'categories' => $categories,
                'items' => $items
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'discount_type' => 'required|in:1,2,3',
            'category_id' => 'required_if:discount_type,2',
            'item_id' => 'required_if:discount_type,3',
            'discount' => 'required|numeric|min:0'
        ]);

        try {
            if ($validated['discount_type'] == 2) {
                Category::find($validated['category_id'])->update([
                    'discount' => $validated['discount']
                ]);
            } elseif ($validated['discount_type'] == 3) {
                Item::find($validated['item_id'])->update([
                    'discount' => $validated['discount']
                ]);
            } else {
                foreach (Item::all() as $item) {
                    $item->update([
                        'discount' => $validated['discount']
                    ]);
                }
            }

            return redirect()->route('discount.create')->with('message', 'Discount Updated Successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('discount.create')->with('message', 'Something went wrong!');
        }
    }
}
