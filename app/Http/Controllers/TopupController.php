<?php

namespace App\Http\Controllers;

use App\Models\TopupProduct;
use Illuminate\Http\Request;

class TopupController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->input('category');

        $query = TopupProduct::with([
            'game:id,name,slug,icon,category',
            'denominations' => function ($q) {
                $q->select('id', 'topup_product_id', 'price');
            },
        ])->orderBy('name');

        if ($category && in_array($category, ['mobile', 'pc', 'console'])) {
            $query->whereHas('game', function ($q) use ($category) {
                $q->where('category', $category);
            });
        }

        $products = $query->get()->each(function ($product) {
            $product->min_price = $product->denominations->min('price') ?? 0;
        });

        return view('topup.index', compact('products', 'category'));
    }

    public function show(TopupProduct $product)
    {
        $product->load([
            'game:id,name,slug,icon,banner,category',
            'denominations' => function ($q) {
                $q->orderBy('price');
            },
        ]);

        return view('topup.show', compact('product'));
    }
}
