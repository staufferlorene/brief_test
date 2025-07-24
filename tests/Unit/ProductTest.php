<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use App\Models\Product;

class ProductTest extends TestCase
{

    use RefreshDatabase;

    // Cette fonction test l'ajout de produit
    public function testCreate()
    {
        // création d'un nouveau produit avec ces données
        $productData = [
            'name' => 'produit ajouté',
            'description' => 'ceci est la description du produit ajouté',
            'price' => 10.50,
            'stock' => 2,
        ];

        // appel de la fonction de création
        $product = Product::create($productData);

        // vérification des données
        $this->assertEquals('produit ajouté', $product->name);
        $this->assertEquals('ceci est la description du produit ajouté', $product->description);
        $this->assertEquals(10.50, $product->price);
        $this->assertEquals(2, $product->stock);
   }


    // Cette fonction test la modification de produit

    public function testEdit()
    {
        // création d'un nouveau produit avec ces données
        $productData = ([
            'name' => 'iPhone 15 Pro',
            'description' => 'Le dernier iPhone avec des fonctionnalités avancées et un design premium.',
            'price' => 1199.99,
            'stock' => 25,
        ]);

        // données modifiées du produit
        $productEdit = ([
            'name' => 'produit modif',
            'description' => 'ceci est la description du produit modif',
            'price' => 150,
            'stock' => 12,
        ]);

        // appel de la fonction de création puis de modification
        $product = Product::create($productData);
        $product->update($productEdit);

        // vérification des données
        $this->assertEquals('produit modif', $product->name);
        $this->assertEquals('ceci est la description du produit modif', $product->description);
        $this->assertEquals(150, $product->price);
        $this->assertEquals(12, $product->stock);
    }

    public function testDelete()
    {
        // Création dans la DB
        $productData = [
            'name' => 'iPhone 15 Pro',
            'description' => 'Le dernier iPhone avec des fonctionnalités avancées et un design premium.',
            'price' => 1199.99,
            'stock' => 25,
        ];

        $product = Product::create($productData);

        // récup id
        $idProduct = $product->id;

        // delete par rapport à l'id
        $product->delete();

        // vérifie que id non présent en DB
        $this->assertDatabaseMissing('products', ['id'=>$idProduct]);
    }
}
