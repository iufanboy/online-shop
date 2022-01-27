<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class DatabaseTest extends TestCase
{
    use RefreshDatabase;


    public function test_product_has_a_name()
    {
        $p = Product::factory()->create(['name' => 'fake name']);

        $this->assertModelExists($p);
        $this->assertEquals('fake name', $p->name);
    }

    public function test_product_must_have_a_name()
    {
        $this->expectException(QueryException::class);

        $p = Product::factory()->create(['name' => null]);

        $this->assertModelMissing($p);
    }

    public function test_product_has_a_description()
    {
        $p = Product::factory()->create(['description' => 'fake description']);

        $this->assertModelExists($p);
        $this->assertEquals('fake description', $p->description);
    }

    public function test_product_must_have_a_description()
    {
        $this->expectException(QueryException::class);

        $p = Product::factory()->create(['description' => null]);

        $this->assertModelMissing($p);
    }

    public function test_product_has_quantity()
    {
        $p = Product::factory()->create(['quantity' => 10]);

        $this->assertModelExists($p);
        $this->assertEquals(10, $p->quantity);
    }

    public function test_product_must_have_quantity()
    {
        $this->expectException(QueryException::class);

        $p = Product::factory()->create(['quantity' => null]);

        $this->assertModelMissing($p);
    }

    public function test_product_has_a_price()
    {
        $p = Product::factory()->create(['price' => 0.1111]);

        $this->assertModelExists($p);
        $this->assertEquals(0.1111, $p->price);
    }

    public function test_product_must_have_a_price()
    {
        $this->expectException(QueryException::class);

        $p = Product::factory()->create(['price' => null]);

        $this->assertModelMissing($p);
    }
}
