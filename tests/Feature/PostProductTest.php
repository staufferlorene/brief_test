<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Illuminate\Foundation\Testing\TestCase;
use App\Models\Product;

class PostProductTest extends TestCase
{

    use RefreshDatabase;

    /*Listes les produits et vérifier le code 200*/
    public function testListProduct(): void
    {
        $response = $this->get('/products');
        $response->assertStatus(200);
    }


 /* Créer un produit via un POST et vérifier qu'il est bien enregistrer en BDD*/

    public function testCreateProduct(): void
    {

        // création du produit
        $productData = [
            'name' => 'test ajout',
            'description' => 'description produit ajouté',
            'price' => 100,
            'stock' => 25,
        ];

        Product::create($productData);

        $response = $this->get('/products');
        $response->assertStatus(200);

        $response->assertSee('test ajout');
        $response->assertSee('description produit ajouté');
        $response->assertSee(100);
        $response->assertSee(25);
    }

    /*Supprimer un produit et vérifier qu'il disparait de la BDD*/

    public function testDeleteProduct(): void
    {
        // création du produit
        $productData = Product::create([
            'name' => 'test ajout',
            'description' => 'description produit ajouté',
            'price' => 100,
            'stock' => 25,
        ]);


        // redirection après delete
        $response = $this->delete("/products/{$productData->id}");
        $response->assertRedirect("/products");
        $response->assertSessionHas('success');

        // produit non visible
        $response->assertDontSee('test ajout');
        $response->assertDontSee('description produit ajouté');
        $response->assertDontSee(100);
        $response->assertDontSee(25);
    }
}
