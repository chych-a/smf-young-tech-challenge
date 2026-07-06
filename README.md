# SMF Young Tech Challenge

Aplikacja Laravel przygotowana jako rozwiązanie zadania rekrutacyjnego **Young Tech Challenge**.

Projekt zawiera REST API, nowoczesny panel webowy, CRUD produktów, upload faktur/paragonów, OCR open-source, prostą ekstrakcję danych z dokumentu oraz zapis wyników do bazy SQLite.

---

## Funkcjonalności

- CRUD produktów
- Upload faktury/paragonu w formacie PDF, JPG, JPEG lub PNG
- OCR z wykorzystaniem rozwiązań open-source:
  - Tesseract OCR dla obrazów
  - Smalot PDF Parser / PDF to Text dla plików PDF
- Prosty agent ekstrakcji danych:
  - Ollama jako lokalny model AI
  - fallback regex, gdy Ollama nie jest dostępna
- Zapis danych do SQLite:
  - contractors
  - invoices
  - invoice_items
  - payments
  - products
- Panel webowy:
  - dashboard z kartami KPI
  - upload dokumentu do OCR
  - lista faktur/paragonów
  - szczegóły dokumentu
  - podgląd OCR
  - podgląd danych wyciągniętych przez AI / regex
  - edycja danych po OCR
  - CRUD produktów
  - zwijany sidebar
  - sticky header w tabelach
  - checkboxy i masowe akcje
  - command palette `Ctrl + K` / `Cmd + K`
  - skrót `N` do szybkiego dodania produktu
- REST API
- Dokumentacja OpenAPI / Swagger
- Docker
- Testy Feature

---

## Technologie

- PHP 8.2+
- Laravel 11
- SQLite
- Tesseract OCR
- Ollama / lokalny model AI
- Blade
- CSS
- JavaScript
- OpenAPI

---

## Wymagania lokalne

Do uruchomienia projektu lokalnie potrzebne są:

- PHP 8.2 lub nowszy
- Composer
- SQLite
- Tesseract OCR
- Git

Opcjonalnie:

- Docker
- Ollama

---

## Instalacja lokalna

### 1. Sklonowanie repozytorium

```bash
git clone https://github.com/TWOJ_LOGIN/smf-young-tech-challenge.git
cd smf-young-tech-challenge
```

W miejscu `TWOJ_LOGIN` należy podać swój login GitHub.

---

### 2. Instalacja zależności

```bash
composer install
```

Jeśli Composer blokuje instalację przez security advisories podczas lokalnego uruchamiania, można tymczasowo wyłączyć blokadę:

```bash
composer config policy.advisories.block false
composer install
```

---

### 3. Konfiguracja środowiska

Skopiuj plik środowiskowy:

```bash
cp .env.example .env
```

Na Windows PowerShell:

```powershell
Copy-Item .env.example .env -Force
```

Wygeneruj klucz aplikacji:

```bash
php artisan key:generate
```

---

### 4. Konfiguracja SQLite

Utwórz plik bazy danych:

```bash
touch database/database.sqlite
```

Na Windows PowerShell:

```powershell
New-Item database\database.sqlite -ItemType File -Force
```

W pliku `.env` ustaw:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Na Windowsie w razie problemu ze ścieżką można ustawić pełną ścieżkę, np.:

```env
DB_CONNECTION=sqlite
DB_DATABASE=C:/Users/your-user/path-to-project/database/database.sqlite
```

---

### 5. Migracje

```bash
php artisan migrate
```

---

### 6. Link do storage

```bash
php artisan storage:link
```

---

### 7. Uruchomienie aplikacji

```bash
php artisan serve
```

Panel webowy będzie dostępny pod adresem:

```text
http://127.0.0.1:8000
```

API będzie dostępne pod adresem:

```text
http://127.0.0.1:8000/api
```

---

## Panel webowy

Panel webowy jest dostępny pod:

```text
http://127.0.0.1:8000
```

Główne widoki:

```text
/                       Dashboard
/invoices               Lista faktur/paragonów
/invoices/create        Upload dokumentu do OCR
/products               Lista produktów
/products/create        Dodawanie produktu
```

Panel zawiera:

- dashboard z podsumowaniem danych,
- karty KPI dla produktów, faktur, pozycji i sumy brutto,
- wykres aktywności OCR,
- upload dokumentu do OCR,
- wizualny pipeline przetwarzania dokumentu,
- listę faktur/paragonów,
- widok szczegółów dokumentu w układzie master-detail,
- podgląd pliku po lewej stronie,
- dane wyciągnięte przez AI / regex po prawej stronie,
- możliwość ręcznej korekty danych po OCR,
- listę produktów,
- formularze tworzenia i edycji produktów,
- masowe akcje w tabelach,
- zwijany sidebar,
- command palette pod `Ctrl + K` lub `Cmd + K`.

---

## REST API

### Health check

```http
GET /api/health
```

---

### Produkty

```http
GET    /api/products
POST   /api/products
GET    /api/products/{id}
PUT    /api/products/{id}
DELETE /api/products/{id}
```

Przykład dodania produktu:

```bash
curl -X POST http://127.0.0.1:8000/api/products \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Produkt testowy",
    "sku": "TEST-001",
    "description": "Produkt dodany przez API",
    "price": 99.99,
    "currency": "PLN",
    "stock": 10
  }'
```

---

### Faktury i paragony

```http
GET    /api/invoices
POST   /api/invoices/upload
GET    /api/invoices/{id}
PATCH  /api/invoices/{id}
DELETE /api/invoices/{id}
```

Przykład uploadu dokumentu:

```bash
curl -X POST http://127.0.0.1:8000/api/invoices/upload \
  -F "file=@samples/faktura_testowa.png"
```

---

## OCR

Aplikacja obsługuje OCR dla:

- plików graficznych: JPG, JPEG, PNG,
- plików PDF.

Dla obrazów wykorzystywany jest Tesseract OCR.

Przykładowa konfiguracja w `.env`:

```env
OCR_LANGUAGE=pol+eng
```

Aby sprawdzić, czy Tesseract jest dostępny:

```bash
tesseract --version
```

Na Windowsie Tesseract można zainstalować osobno i dodać do zmiennej środowiskowej `PATH`.

Przykładowa ścieżka:

```text
C:\Program Files\Tesseract-OCR
```

---

## Agent AI i fallback regex

Po wykonaniu OCR aplikacja przekazuje tekst dokumentu do warstwy ekstrakcji danych.

Obsługiwane są dwa tryby:

```env
AI_PROVIDER=ollama
```

lub:

```env
AI_PROVIDER=regex
```

Tryb `ollama` próbuje skorzystać z lokalnego modelu AI.

Przykładowa konfiguracja:

```env
OLLAMA_BASE_URL=http://localhost:11434
OLLAMA_MODEL=llama3.1:8b
```

Jeżeli Ollama nie jest dostępna, aplikacja może użyć fallbacku regex.

Fallback regex próbuje wydobyć m.in.:

- nazwę kontrahenta,
- adres,
- NIP,
- numer dokumentu,
- datę wystawienia,
- kwotę,
- walutę,
- metodę płatności,
- pozycje dokumentu.

---

## Przykładowy plik testowy

W repozytorium znajduje się przykładowy dokument testowy:

```text
samples/faktura_testowa.png
```

Można go wykorzystać do testów w panelu webowym albo przez API.

---

## Dokumentacja OpenAPI

Dokumentacja API znajduje się w pliku:

```text
docs/openapi.yaml
```

Po instalacji zależności Swagger można wygenerować komendą:

```bash
php artisan l5-swagger:generate
```

---

## Testy

Uruchomienie testów:

```bash
php artisan test
```

Testy obejmują m.in.:

- podstawowy CRUD produktów,
- walidację uploadu dokumentu.

---

## Docker

Projekt zawiera podstawową konfigurację Docker.

Uruchomienie:

```bash
docker compose up --build
```

Po uruchomieniu aplikacja będzie dostępna pod:

```text
http://localhost:8000
```

Jeżeli wykorzystywana jest Ollama w Dockerze, należy pobrać model:

```bash
docker compose exec ollama ollama pull llama3.1:8b
```

---

## Struktura bazy danych

Minimalny schemat bazy obejmuje:

```text
contractors
invoices
invoice_items
payments
products
```

W tabeli `invoices` zapisywane są również:

- ścieżka do przesłanego pliku,
- oryginalna nazwa pliku,
- pełny tekst OCR,
- JSON z ekstrakcji AI / regex,
- status przetwarzania.

---

## Przykładowy scenariusz działania

1. Użytkownik przesyła fakturę albo paragon przez panel webowy lub API.
2. Plik zostaje zapisany w storage aplikacji.
3. Serwis OCR odczytuje tekst z PDF/JPG/PNG.
4. Tekst trafia do agenta AI albo fallbacku regex.
5. Dane zostają zamienione na strukturę faktury.
6. Aplikacja zapisuje kontrahenta, fakturę, pozycje i płatności w SQLite.
7. Użytkownik może podejrzeć wynik w panelu.
8. Użytkownik może ręcznie poprawić dane po OCR.

---

## Przydatne komendy

Wyczyszczenie cache:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

Odświeżenie bazy danych:

```bash
php artisan migrate:fresh
```

Lista tras:

```bash
php artisan route:list
```

Uruchomienie serwera:

```bash
php artisan serve
```

---

## Bezpieczeństwo repozytorium

Do repozytorium nie powinny być dodawane:

```text
.env
vendor/
node_modules/
database/database.sqlite
storage/logs/
```

W repozytorium powinny znajdować się:

```text
.env.example
README.md
composer.json
app/
routes/
resources/
database/migrations/
docs/openapi.yaml
samples/faktura_testowa.png
Dockerfile
docker-compose.yml
tests/
```

---

## Autor

Igor Mierzejek

---

## Cel projektu

Projekt został przygotowany jako rozwiązanie zadania rekrutacyjnego **Young Tech Challenge**.

Celem było pokazanie umiejętności pracy z backendem Laravel, przetwarzaniem plików, OCR, prostą ekstrakcją danych, bazą SQL, REST API, dokumentacją oraz panelem webowym.