<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $allItems = Item::all();

        $assembled = $allItems->filter(function ($item) {
            return !empty($item->components) && count($item->components) > 0;
        });

        $base = $allItems->filter(function ($item) {
            return empty($item->components) || count($item->components) === 0;
        });

        return view('items.index', [
            'groupedItems' => [
                'Assembled Artifacts' => $assembled,
                'Base Armaments' => $base,
            ]
        ]);
    }

    public function show($id)
    {
        $item = Item::findOrFail($id);
        
        return view('items.show', compact('item'));
    }
}