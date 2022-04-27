<?php

namespace Tests\Feature;

use App\Models\Product\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_product_index()
    {
        $user = User::find(1);

        $response = $this->actingAs($user)->get('/product');

        $response->assertStatus(200);
    }

    public function test_product_create()
    {
        $user = User::find(1);

        $response = $this->actingAs($user)
            ->post('/product', [
                'name' => 'product 1',
                'price' => rand(10, 100),
            ]);

        $response->assertRedirect('/product');
        $this->actingAs($user)->get('/product')->assertSeeText('product 1');
    }

    public function test_product_update()
    {
        $user = User::find(1);

        $product = Product::create([
            'name' => 'product 1',
            'price' => rand(10, 100),
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->put('/product/' . $product->id, [
            'name' => 'product 1 update',
            'price' => 99
        ]);

        $response = $this->actingAs($user)->get('/product');

        $response->assertSeeText('product 1 update') && $response->assertSeeText(99);
    }

    public function test_product_delete()
    {
        $user = User::find(1);

        $product = Product::create([
            'name' => 'product 1 deleted',
            'price' => rand(10, 100),
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get('/product');

        $response->assertSeeText('product 1 deleted');

        $response = $this->actingAs($user)->delete('/product/' . $product->id);

        $response->assertRedirect('/product');

        $response->assertDontSeeText('product 1 deleted');
    }
}
