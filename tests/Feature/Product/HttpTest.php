<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class HttpTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_user_can_view_products()
    {
        $response = $this->getJson('/api/products');

        $response->assertStatus(200);
    }

    public function test_auth_user_can_view_products()
    {
        $response = $this->getJson('/api/products');

        $response->assertStatus(200);
    }

    public function test_each_product_has_exact_json()
    {
        Product::factory(5)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $jsons) =>
                $jsons->has(5)
                    ->each(
                        fn (AssertableJson $json) =>
                        $json->hasAll(['id', 'name', 'description', 'quantity', 'price', 'created_at', 'updated_at'])
                    )
            );
    }
}
