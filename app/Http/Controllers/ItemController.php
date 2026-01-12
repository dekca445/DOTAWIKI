<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        // Ambil semua item
        $allItems = Item::all();

        // Filter Manual: Item Jadi vs Item Dasar
        $assembled = $allItems->filter(function ($item) {
            // Cek apakah kolom components ada isinya (array tidak kosong)
            return !empty($item->components) && count($item->components) > 0;
        });

        $base = $allItems->filter(function ($item) {
            // Item dasar = components kosong ATAU null
            return empty($item->components) || count($item->components) === 0;
        });

        // Kirim ke view dalam format Group
        return view('items.index', [
            'groupedItems' => [
                'Assembled Artifacts' => $assembled,
                'Base Armaments' => $base,
            ]
        ]);
    }

    public function show($id)
    {
        // Cari item, jika gagal 404
        $item = Item::findOrFail($id);
        
        return view('items.show', compact('item'));
    }
}