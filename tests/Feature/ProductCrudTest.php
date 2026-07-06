<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    public function test_product_crud_flow(): void
    {
        $created = $this->postJson('/api/products', [
            'name' => 'Test product',
            'sku' => 'TEST-001',
            'description' => 'Example',
            'price' => 10.50,
            'currency' => 'PLN',
            'stock' => 3,
        ])->assertCreated()->json();

        $this->getJson('/api/products/'.$created['id'])->assertOk()->assertJsonPath('name', 'Test product');

        $this->putJson('/api/products/'.$created['id'], [
            'name' => 'Updated product',
            'sku' => 'TEST-001',
            'description' => 'Updated',
            'price' => 12.00,
            'currency' => 'PLN',
            'stock' => 4,
        ])->assertOk()->assertJsonPath('name', 'Updated product');

        $this->deleteJson('/api/products/'.$created['id'])->assertNoContent();
    }
}
