<?php

return [
    'ocr_language' => env('OCR_LANGUAGE', 'pol+eng'),
    'tesseract_binary' => env('TESSERACT_BINARY', 'tesseract'),
    'pdftotext_binary' => env('PDFTOTEXT_BINARY', 'pdftotext'),
    'ai_provider' => env('AI_PROVIDER', 'ollama'),
    'ollama_url' => env('OLLAMA_URL', 'http://127.0.0.1:11434'),
    'ollama_model' => env('OLLAMA_MODEL', 'llama3.1:8b'),
    'ai_timeout' => (int) env('AI_TIMEOUT', 60),
];
