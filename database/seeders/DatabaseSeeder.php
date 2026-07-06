<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Product::query()->firstOrCreate(
            ['sku' => 'DEMO-001'],
            [
                'name' => 'Usługa wdrożeniowa',
                'description' => 'Przykładowy rekord do testów CRUD.',
                'price' => 199.00,
                'currency' => 'PLN',
                'stock' => 5,
            ]
        );
    }
}
