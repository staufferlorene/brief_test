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

    // Modifier un produit existant et vérifier que la mise à jour est correct
    public function testUpdateProduct(): void
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

        $response = $this->put("/products/{$product->id}", $productEdit);

        // redirection après màj
        $response->assertRedirect('/products');

        // success sur la vue
        $response->assertSessionHas('success');

        // vérifier que présent en DB
        $this->assertDatabaseHas('products', $productEdit);
    }

    /*Supprimer un produit*/

    public function testDeleteProduct(): void
    {
        // création du produit
        $productData = Product::create([
            'name' => 'test ajout',
            'description' => 'description produit ajouté',
            'price' => 100,
            'stock' => 25,
        ]);

        // delete du produit par rapport à son id
        $response = $this->delete("/products/{$productData->id}");
        // redirection après delete
        $response->assertRedirect('/products');
        // success sur la vue
        $response->assertSessionHas('success');

        // produit non visible
        $response->assertDontSee('test ajout');
        $response->assertDontSee('description produit ajouté');
        $response->assertDontSee(100);
        $response->assertDontSee(25);
    }

    // Vérification des erreurs de validation
    public function testValidate(): void
    {
        // création du produit
        $product = Product::create([
            'name' => '',
            'description' => 'description produit ajouté',
            'price' => -2,
            'stock' => 25,
        ]);

        $this->assertDatabaseMissing('products', [$product]);
    }
}
