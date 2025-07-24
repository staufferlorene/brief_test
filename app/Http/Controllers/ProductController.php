<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        $products = Product::orderBy('created_at', 'desc')->get();
        return view('products.index', compact('products'));
    }

    public function create() {
        return view('products.create');
    }

    public function store(Request $request) {
        // Validation simplifiée pour permettre aux apprenants de créer des tests
        $request->validate([
            'name' => 'required|string|max:40',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|decimal:0,2|min:0',
            'stock' => 'required|integer',
        ]);

        Product::create($request->all());
        return redirect()->route('products.index')->with('success', 'Produit ajouté avec succès !');
    }

    public function show(Product $product) {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product) {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product) {
        $request->validate([
            'name' => 'required|string|max:40',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|decimal:0,2|min:0',
            'stock' => 'required|integer',
        ]);

        $product->update($request->all());
        return redirect()->route('products.index')->with('success', 'Produit mis à jour avec succès !');
    }

    public function destroy(Product $product) {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produit supprimé avec succès !');
    }
}
