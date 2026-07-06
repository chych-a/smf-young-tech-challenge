<?php

namespace App\Services;

use GuzzleHttp\Client;

class AiExtractionService
{
    public function __construct(
        private readonly RegexExtractionService $fallbackExtractor,
    ) {}

    public function extract(string $ocrText): array
    {
        if (config('challenge.ai_provider') !== 'ollama') {
            return $this->fallbackExtractor->extract($ocrText);
        }

        try {
            $payload = $this->extractWithOllama($ocrText);

            if ($payload !== []) {
                return $payload;
            }
        } catch (\Throwable) {
            // Local model may be unavailable during development; regex fallback keeps the API usable.
        }

        return $this->fallbackExtractor->extract($ocrText);
    }

    private function extractWithOllama(string $ocrText): array
    {
        $client = new Client([
            'base_uri' => rtrim(config('challenge.ollama_url'), '/'),
            'timeout' => config('challenge.ai_timeout'),
        ]);

        $response = $client->post('/api/chat', [
            'json' => [
                'model' => config('challenge.ollama_model'),
                'stream' => false,
                'format' => 'json',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Jesteś agentem ekstrakcji danych z polskich faktur i paragonów. Zwracasz wyłącznie poprawny JSON zgodny ze schematem.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $this->prompt($ocrText),
                    ],
                ],
            ],
        ]);

        $decoded = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $content = $decoded['message']['content'] ?? '{}';
        $payload = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        return is_array($payload) ? $this->normalize($payload) : [];
    }

    private function prompt(string $ocrText): string
    {
        return <<<PROMPT
Wyciągnij dane z poniższego tekstu OCR i zwróć JSON:
{
  "contractor": {"name": null, "address": null, "tax_id": null},
  "invoice": {"number": null, "issued_at": null, "total_amount": null, "currency": "PLN"},
  "items": [{"name": null, "quantity": null, "unit_price": null, "line_total": null}],
  "payments": [{"amount": null, "currency": "PLN", "method": null, "paid_at": null}]
}

Zasady:
- Daty zwracaj jako YYYY-MM-DD, gdy da się ustalić.
- Kwoty zwracaj jako liczby, bez symboli waluty.
- Jeżeli danych brakuje, użyj null lub pustej tablicy.

Tekst OCR:
---
{$ocrText}
---
PROMPT;
    }

    private function normalize(array $payload): array
    {
        return [
            'contractor' => $payload['contractor'] ?? [],
            'invoice' => $payload['invoice'] ?? [],
            'items' => array_values($payload['items'] ?? []),
            'payments' => array_values($payload['payments'] ?? []),
        ];
    }
}
