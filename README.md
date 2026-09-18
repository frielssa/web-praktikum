# Dokumentasi Proyek Laravel - Modul Tickets

Dokumentasi ini berisi panduan instalasi proyek dari klona baru (fresh clone), konfigurasi lingkungan, perintah eksekusi database, serta struktur berkas artefak praktikum pada direktori `docs/`.

---

## 1. Versi Framework & DBMS

- **PHP Version:** PHP 8.5.10 (cli) x64 NTS
- **Laravel Version:** Laravel Framework 13.32.0
- **DBMS:** MySQL 8.0 / MariaDB
- **Database Target:** `db_tickets_latihan`

---

## 2. Konfigurasi Environment (`.env.example`)

Template berkas `.env.example` tanpa kredensial sensitif:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Web_Praktek
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120