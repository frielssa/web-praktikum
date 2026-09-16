# Proyek Web Praktikum
# Praktikum Pemrograman Web 2

## Deskripsi
Aplikasi sederhana menggunakan Laravel sebagai backend dan Vue sebagai frontend.

## Teknologi
- PHP dan Laravel
- Composer
- Vue dan Vite
- Node.js dan NPM
- MySQL
- Git

## Instalasi Backend
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## Instalasi Frontend
```bash
cd frontend
npm install
npm run dev
```

## Arsitektur
```mermaid
sequenceDiagram
    autonumber
    actor User as User / Browser
    participant Vue as Vue.js (Frontend)
    participant Laravel as Laravel API (Backend)
    participant DB as Database (MySQL)

    Note over User, Vue: CLIENT SIDE
    User->>Vue: 1. Interaksi UI (Submit Form / Click)
    Note over Vue, Laravel: Data Format: JSON (HTTP Request)
    Vue->>Laravel: 2. HTTP Request (GET/POST/PUT/DELETE)
    
    Note over Laravel, DB: SERVER SIDE
    Note over Laravel: Validasi & Logic Business
    Note over Laravel, DB: Data Format: SQL Query
    Laravel->>DB: 3. Exec Query (SELECT / INSERT / UPDATE)
    Note over Laravel, DB: Data Format: Raw Data (Rows/Columns)
    DB-->>Laravel: 4. Return Raw Data
    
    Note over Laravel: Eloquent ORM Serializes Data to JSON
    Note over Vue, Laravel: Data Format: JSON (HTTP Response)
    Laravel-->>Vue: 5. HTTP Response (JSON Payload + Status Code)
    
    Note over Vue, User: Update Reactive State
    Vue-->>User: 6. Re-render DOM / Tampilan Baru
```
