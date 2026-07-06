<?php

namespace App\Services;

use Smalot\PdfParser\Parser as PdfParser;
use Spatie\PdfToText\Pdf;
use thiagoalessio\TesseractOCR\TesseractOCR;

class OcrService
{
    public function extract(string $absolutePath, ?string $extension = null): string
    {
        $extension = strtolower((string) $extension);

        $text = $extension === 'pdf'
            ? $this->extractFromPdf($absolutePath)
            : $this->extractFromImage($absolutePath);

        $text = trim(preg_replace('/[ \t]+/', ' ', $text));

        if ($text === '') {
            throw new \RuntimeException('OCR nie zwrócił tekstu. Sprawdź jakość pliku lub instalację tesseract/pdftotext.');
        }

        return $text;
    }

    private function extractFromPdf(string $absolutePath): string
    {
        $text = '';

        try {
            $text = trim((new PdfParser())->parseFile($absolutePath)->getText());
        } catch (\Throwable) {
            $text = '';
        }

        if ($text !== '') {
            return $text;
        }

        try {
            return trim(Pdf::getText($absolutePath, config('challenge.pdftotext_binary')));
        } catch (\Throwable) {
            return '';
        }
    }

    private function extractFromImage(string $absolutePath): string
    {
        return (new TesseractOCR($absolutePath))
            ->executable(config('challenge.tesseract_binary'))
            ->lang(...explode('+', config('challenge.ocr_language')))
            ->run();
    }
}
