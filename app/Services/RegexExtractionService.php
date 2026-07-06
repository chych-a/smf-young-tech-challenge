<?php

namespace App\Services;

use Carbon\Carbon;

class RegexExtractionService
{
    public function extract(string $ocrText): array
    {
        $normalized = $this->normalizeText($ocrText);

        return [
            'contractor' => [
                'name' => $this->extractContractorName($normalized),
                'address' => $this->extractAddress($normalized),
                'tax_id' => $this->extractNip($normalized),
            ],
            'invoice' => [
                'number' => $this->extractDocumentNumber($normalized),
                'issued_at' => $this->extractDate($normalized),
                'total_amount' => $this->extractTotalAmount($normalized),
                'currency' => $this->extractCurrency($normalized),
            ],
            'items' => $this->extractItems($normalized),
            'payments' => [[
                'amount' => $this->extractTotalAmount($normalized),
                'currency' => $this->extractCurrency($normalized),
                'method' => $this->extractPaymentMethod($normalized),
                'paid_at' => $this->extractDate($normalized),
            ]],
        ];
    }

    private function normalizeText(string $text): string
    {
        return trim(str_replace("\r", "\n", preg_replace('/[\t ]+/', ' ', $text)));
    }

    private function extractNip(string $text): ?string
    {
        preg_match('/(?:NIP|VAT\s*ID)[:\s-]*([0-9\- ]{10,15})/iu', $text, $matches);

        return isset($matches[1]) ? preg_replace('/\D+/', '', $matches[1]) : null;
    }

    private function extractDocumentNumber(string $text): ?string
    {
        preg_match('/(?:Faktura|Paragon|FV|Numer)[:\s#-]*([A-Z0-9\/\-]+)\b/iu', $text, $matches);

        return $matches[1] ?? null;
    }

    private function extractDate(string $text): ?string
    {
        preg_match('/(\d{4}[-.]\d{2}[-.]\d{2}|\d{2}[-.]\d{2}[-.]\d{4})/', $text, $matches);

        if (! isset($matches[1])) {
            return null;
        }

        $formats = ['Y-m-d', 'Y.m.d', 'd-m-Y', 'd.m.Y'];
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $matches[1])->format('Y-m-d');
            } catch (\Throwable) {
                // Try next format.
            }
        }

        return null;
    }

    private function extractTotalAmount(string $text): ?float
    {
        $patterns = [
            '/(?:Razem do zapłaty|Do zapłaty|Suma|Razem|Total|Kwota)[:\s]*([0-9\s]+[,.][0-9]{2})/iu',
            '/([0-9\s]+[,.][0-9]{2})\s*(?:PLN|zł|EUR|USD)/iu',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $text, $matches) && isset($matches[1])) {
                $last = end($matches[1]);

                return $this->moneyToFloat($last);
            }
        }

        return null;
    }

    private function extractCurrency(string $text): string
    {
        if (preg_match('/\b(EUR|USD|GBP|PLN)\b/iu', $text, $matches)) {
            return strtoupper($matches[1]);
        }

        if (preg_match('/\bzł\b/iu', $text)) {
            return 'PLN';
        }

        return 'PLN';
    }

    private function extractPaymentMethod(string $text): ?string
    {
        return match (true) {
            preg_match('/\b(karta|card|terminal)\b/iu', $text) === 1 => 'card',
            preg_match('/\b(gotówka|cash)\b/iu', $text) === 1 => 'cash',
            preg_match('/\b(przelew|transfer)\b/iu', $text) === 1 => 'bank_transfer',
            default => null,
        };
    }

    private function extractContractorName(string $text): ?string
    {
        $lines = array_values(array_filter(array_map('trim', explode("\n", $text))));

        foreach ($lines as $line) {
            if (preg_match('/(sprzedawca|seller|wystawca)[:\s]*(.+)$/iu', $line, $matches)) {
                return trim($matches[2]);
            }
        }

        foreach ($lines as $line) {
            if (! preg_match('/(faktura|paragon|nip|data|razem|suma|adres)/iu', $line) && mb_strlen($line) > 4) {
                return $line;
            }
        }

        return null;
    }

    private function extractAddress(string $text): ?string
    {
        if (preg_match('/(?:Adres|Address)[:\s]*(.+)/iu', $text, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    private function extractItems(string $text): array
    {
        $items = [];
        $lines = array_filter(array_map('trim', explode("\n", $text)));

        foreach ($lines as $line) {
            if (preg_match('/^(.+?)\s+(\d+[,.]?\d*)\s+x?\s*([0-9\s]+[,.][0-9]{2})\s+([0-9\s]+[,.][0-9]{2})$/u', $line, $matches)) {
                $items[] = [
                    'name' => trim($matches[1]),
                    'quantity' => $this->moneyToFloat($matches[2]),
                    'unit_price' => $this->moneyToFloat($matches[3]),
                    'line_total' => $this->moneyToFloat($matches[4]),
                ];
            }
        }

        return $items;
    }

    private function moneyToFloat(?string $value): ?float
    {
        if ($value === null) {
            return null;
        }

        return (float) str_replace(',', '.', preg_replace('/\s+/', '', $value));
    }
}
