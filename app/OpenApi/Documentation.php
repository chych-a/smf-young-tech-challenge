<?php

namespace App\OpenApi;

/**
 * @OA\Info(
 *     title="SMF Young Tech Challenge API",
 *     version="1.0.0",
 *     description="REST API dla CRUD produktów oraz uploadu faktur/paragonów z OCR i ekstrakcją AI."
 * )
 * @OA\Server(url="http://localhost:8000", description="Local development")
 * @OA\Schema(
 *     schema="ProductInput",
 *     required={"name", "price", "currency", "stock"},
 *     @OA\Property(property="name", type="string", example="Usługa wdrożeniowa"),
 *     @OA\Property(property="sku", type="string", nullable=true, example="DEMO-001"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="price", type="number", format="float", example=199.00),
 *     @OA\Property(property="currency", type="string", example="PLN"),
 *     @OA\Property(property="stock", type="integer", example=5)
 * )
 */
final class Documentation
{
}
