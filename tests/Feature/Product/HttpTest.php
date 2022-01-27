<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Illuminate\Support\Str;

class HttpTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_user_can_view_products()
    {
        $response = $this->getJson(route('products.index'));

        $response->assertOk(200);
    }

    public function test_auth_user_can_view_products()
    {
        $response = $this->getJson(route('products.index'));

        $response->assertStatus(200);
    }

    public function test_each_product_has_exact_json()
    {
        Product::factory(5)->create();

        $response = $this->getJson(route('products.index'));

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

    public function test_guest_user_can_create_product()
    {
        $response = $this->postJson(route('products.store'));

        $response->assertUnprocessable();
    }

    public function test_auth_user_can_create_product()
    {
        /**@var User $user */
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson(route('products.store'));

        $response->assertUnprocessable();
    }

    public function test_product_requires_a_name_to_create()
    {
        $p = Product::factory()->make(['name' => '']);

        $response = $this->postJson(route('products.store', $p->toArray()));

        $response->assertUnprocessable()->assertInvalid(['name']);
    }

    public function test_product_name_must_not_exceed_255()
    {
        $p = Product::factory()->make(['name' => Str::random(256)]);

        $response = $this->postJson(route('products.store', $p->toArray()));

        $response->assertUnprocessable()->assertInvalid(['name']);
    }

    public function test_product_requires_description_to_create()
    {
        $p = Product::factory()->make(['description' => '']);

        $response = $this->postJson(route('products.store', $p->toArray()));

        $response->assertUnprocessable()->assertInvalid(['description']);
    }

    public function test_product_requires_quanity_to_create()
    {
        $p = Product::factory()->make(['quantity' => null]);

        $response = $this->postJson(route('products.store', $p->toArray()));

        $response->assertUnprocessable()->assertInvalid(['quantity']);
    }

    public function test_product_quantity_must_not_subceed_0()
    {
        $p = Product::factory()->make(['quantity' => -1]);

        $response = $this->postJson(route('products.store', $p->toArray()));

        $response->assertUnprocessable()->assertInvalid(['quantity']);
    }

    public function test_product_requires_price_to_create()
    {
        $p = Product::factory()->make(['price' => null]);

        $response = $this->postJson(route('products.store', $p->toArray()));

        $response->assertUnprocessable()->assertInvalid(['price']);
    }

    public function test_product_price_must_not_subceed_0()
    {
        $p = Product::factory()->make(['price' => -0.11212]);

        $response = $this->postJson(route('products.store', $p->toArray()));

        $response->assertUnprocessable()->assertInvalid(['price']);
    }

    public function test_product_can_create_with_valid_attributes()
    {
        $p = Product::factory()->make();

        $response = $this->postJson(route('products.store', $p->toArray()));

        $response->assertCreated()
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->whereAll($p->toArray())
                    ->etc()
            );
    }

    public function test_guest_user_can_view_product()
    {
        $p = Product::factory()->create();

        $response = $this->getJson(route('products.show', ['product' => $p]));

        $response->assertStatus(200);
    }

    public function test_auth_user_can_view_product()
    {
        $p = Product::factory()->create();

        $response = $this->getJson(route('products.show', ['product' => $p]));

        $response->assertStatus(200);
    }

    public function test_product_has_exact_json()
    {
        $p = Product::factory()->create();

        $response = $this->getJson(route('products.show', ['product' => $p]));

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->hasAll(['id', 'name', 'description', 'quantity', 'price', 'created_at', 'updated_at'])
            );
    }
}
