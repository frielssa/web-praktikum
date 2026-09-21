# Dokumentasi Kasus Uji CRUD & Transaksi (Modul 7)

## Standar Format Pencatatan
- **Lokasi File:** `docs/kasus-uji-crud.md`
- **Konvensi String:** Menggunakan karakter ASCII agar perhitungan panjang karakter presisi.
- **Isolasi Field:** Saat menguji satu *field* spesifik, *field* lainnya diisi dengan nilai yang valid.

---

### TC-01: Create Valid
- **Prasyarat / ID Record:** User terautentikasi (User ID: 1), Category ID: 1
- **Payload Penting:** 
  - `subject`: `Tiket Perbaikan Server`
  - `description`: `Deskripsi kendala teknis pada server utama`
  - `category_id`: `1`
  - `user_id`: `1`
  - `initial_note`: `Catatan awal tiket`
- **Langkah:** Isi seluruh field form pembuatan tiket dengan data valid, lalu klik **Simpan**.
- **Expected Result:** Status HTTP `303 See Other` (redirect ke detail tiket), status tiket `open`, jumlah record `tickets` bertambah +1, dan `comments` bertambah +1.
- **Actual Result:** Sesuai expected.
- **Method / Status Awal:** `POST /tickets` -> `303 See Other` -> `GET /tickets/{id}` -> `200 OK`
- **Keadaan Database Sebelum:** `tickets` (n), `comments` (m)
- **Keadaan Database Sesudah:** `tickets` (n+1), `comments` (m+1)
- **Status:** PASS
- **Bukti:** Log Network DevTools (`POST 303`), Halaman detail tiket terbuka.

---

### TC-02: Input Kosong (Validation Failure)
- **Prasyarat / ID Record:** -
- **Payload Penting:**
  - `subject`: `""`
  - `description`: `""`
  - `initial_note`: `""`
- **Langkah:** Hapus atribut `required` HTML5 via browser/DevTools, kosongkan semua field, lalu submit form.
- **Expected Result:** Terjadi redirect `302 Found`, muncul pesan error validasi di form, dan tidak ada record baru di database.
- **Actual Result:** Sesuai expected.
- **Method / Status Awal:** `POST /tickets` -> `302 Found`
- **Keadaan Database Sebelum:** `tickets` (n), `comments` (m)
- **Keadaan Database Sesudah:** `tickets` (n), `comments` (m)
- **Status:** PASS
- **Bukti:** Pesan error *"The subject field is required"* tampil pada form.

---

### TC-03: Input Spasi Saja (Trim Check)
- **Prasyarat / ID Record:** -
- **Payload Penting:**
  - `subject`: `"   "`
  - `description`: `Deskripsi valid`
  - `category_id`: `1`
- **Langkah:** Masukkan 3 spasi pada field `subject`, isi field lain secara valid, lalu submit.
- **Expected Result:** Middleware/FormRequest melakukan *trim*, subject menjadi string kosong, validasi `required` menolak input.
- **Actual Result:** Sesuai expected.
- **Method / Status Awal:** `POST /tickets` -> `302 Found`
- **Keadaan Database Sebelum:** `tickets` (n)
- **Keadaan Database Sesudah:** `tickets` (n)
- **Status:** PASS
- **Bukti:** Error validasi subject ditolak karena dianggap kosong.

---

### TC-04: Batas Karakter Subject (149, 150, 151)
- **Prasyarat / ID Record:** Skenario Create & Update (Ticket ID: 1)
- **Payload Penting:**
  - Uji 149 ASCII: `"A"` x 149
  - Uji 150 ASCII: `"A"` x 150
  - Uji 151 ASCII: `"A"` x 151
- **Langkah:** Kirim form pada aksi Create dan Update secara bergantian menggunakan panjang string subject di atas.
- **Expected Result:** 149 dan 150 karakter lolos validasi (DB tersimpan). 151 karakter gagal validasi (error `max:150`, DB tidak berubah).
- **Actual Result:** Sesuai expected.
- **Method / Status Awal:** `POST /tickets` atau `PUT /tickets/1`
- **Keadaan Database Sebelum:** `subject` lama
- **Keadaan Database Sesudah:** 149/150 tersimpan; 151 tetap nilai lama.
- **Status:** PASS
- **Bukti:** Pesan error max length muncul khusus pada payload 151 karakter.

---

### TC-05: Batas Description (5000/5001) dan Initial Note (1000/1001)
- **Prasyarat / ID Record:** -
- **Payload Penting:**
  - `description`: 5000 ASCII vs 5001 ASCII
  - `initial_note`: 1000 ASCII vs 1001 ASCII
- **Langkah:** Kirim payload dengan batas pas (5000 / 1000) lalu kirim payload lebih satu karakter (5001 / 1001).
- **Expected Result:** Tepat batas (5000 & 1000) lolos. Lebih satu karakter (5001 & 1001) ditolak oleh validasi `max`.
- **Actual Result:** Sesuai expected.
- **Method / Status Awal:** `POST /tickets` -> `302 Found` (saat > batas)
- **Keadaan Database Sebelum:** `tickets` (n)
- **Keadaan Database Sesudah:** Hanya payload tepat batas yang masuk ke database.
- **Status:** PASS
- **Bukti:** Respons error validasi untuk deskripsi/note yang melebihi batas.

---

### TC-06: Identifier Relasi Tidak Sah (Foreign Key Integrity)
- **Prasyarat / ID Record:** -
- **Payload Penting:**
  - Skenario A: `category_id`: `99999` (angka tidak ada)
  - Skenario B: `category_id`: `"abc"` (string)
  - Skenario C: `user_id`: `99999`
- **Langkah:** Kirim payload create/update dengan memanipulasi ID relasi yang tidak valid.
- **Expected Result:** Validasi `exists` menolak request, mengembalikan error validasi, tanpa ada query mutation ke database.
- **Actual Result:** Sesuai expected.
- **Method / Status Awal:** `POST /tickets` -> `302 Found` (atau `422 Unprocessable Content` via API)
- **Keadaan Database Sebelum:** `tickets` (n)
- **Keadaan Database Sesudah:** `tickets` (n)
- **Status:** PASS
- **Bukti:** Error *"The selected category id is invalid"*.

---

### TC-07: Identifier Route Tidak Sah (404 Handling)
- **Prasyarat / ID Record:** Sesi dan CSRF valid
- **Langkah:** Akses Rute berikut via Browser/HTTP Client:
  1. `GET /tickets/abc`
  2. `GET /tickets/99999/edit`
  3. `PUT /tickets/99999`
  4. `DELETE /tickets/99999`
- **Expected Result:** Mengembalikan status HTTP `404 Not Found`, database tidak mengalami perubahan.
- **Actual Result:** Sesuai expected.
- **Method / Status Awal:** `GET` / `PUT` / `DELETE` -> `404 Not Found`
- **Keadaan Database Sebelum:** `tickets` (n)
- **Keadaan Database Sesudah:** `tickets` (n)
- **Status:** PASS
- **Bukti:** Tampilan halaman 404 Not Found Laravel.

---

### TC-08: Update Valid
- **Prasyarat / ID Record:** Ticket ID: 1 (Pemilik ID: 1)
- **Payload Penting:**
  - `subject`: `Subject Diperbarui`
  - `category_id`: `2`
  - `status`: `pending`
  - `note`: `Penambahan catatan tindak lanjut`
- **Langkah:** Buka form edit tiket ID 1, ubah field di atas, lalu simpan.
- **Expected Result:** Data tiket tersimpan dengan perubahan baru, `user_id` (pemilik) tidak berubah, dan `comments` bertambah +1.
- **Actual Result:** Sesuai expected.
- **Method / Status Awal:** `PUT /tickets/1` -> `303 See Other`
- **Keadaan Database Sebelum:** `tickets.status` = `open`, `comments` (m)
- **Keadaan Database Sesudah:** `tickets.status` = `pending`, `comments` (m+1)
- **Status:** PASS
- **Bukti:** Perubahan data terlihat pada halaman detail dan komentar baru bertambah.

---

### TC-09: Update Invalid
- **Prasyarat / ID Record:** Ticket ID: 1
- **Payload Penting:**
  - `subject`: `"A"` x 151
  - `status`: `unknown_status`
- **Langkah:** Jalankan pembaruan data tiket dengan menyisipkan subject berlebih dan nilai status yang tidak terdaftar.
- **Expected Result:** Validasi menolak request, data `subject` dan `status` di DB tetap menggunakan nilai lama, komentar tidak bertambah.
- **Actual Result:** Sesuai expected.
- **Method / Status Awal:** `PUT /tickets/1` -> `302 Found`
- **Keadaan Database Sebelum:** Data lama Ticket ID 1
- **Keadaan Database Sesudah:** Data Ticket ID 1 tidak berubah.
- **Status:** PASS
- **Bukti:** Tampil pesan error validasi pada form edit.

---

### TC-10: Manipulasi Field Terlarang (Prohibited Rule)
- **Prasyarat / ID Record:** Ticket ID: 1
- **Payload Penting:**
  - Skenario Create: `status` = `closed`
  - Skenario Update: `user_id` = `2` (Mencoba mengubah pemilik)
- **Langkah:** Kirim payload yang mengandung field terlarang/tidak diizinkan.
- **Expected Result:** Rule `prohibited` atau penanganan `validated()` pada Controller membuang/menolak field terlarang tersebut. Data yang masuk ke persistence hanya field yang terdaftar eksplisit.
- **Actual Result:** Sesuai expected.
- **Method / Status Awal:** `POST /tickets` atau `PUT /tickets/1`
- **Keadaan Database Sebelum:** `user_id` = `1`, `status` = `open`
- **Keadaan Database Sesudah:** `user_id` tetap `1`, status create tetap awal (`open`).
- **Status:** PASS
- **Bukti:** Field terlarang diabaikan atau memicu error validasi.

---

### TC-11: Urgensi Checkbox Parsing
- **Prasyarat / ID Record:** Ticket ID: 1
- **Payload Penting:**
  - Tes A: `is_urgent` = `1` (centang)
  - Tes B: `is_urgent` = `0` / absent (hapus centang)
  - Tes C: `is_urgent` = `"abc"` (string tidak sah)
- **Langkah:** Simpan tiket dengan centang aktif, hapus centang saat edit, dan coba kirim string `"abc"` via HTTP Client.
- **Expected Result:** Tes A menghasilkan `true` (`1`), Tes B menghasilkan `false` (`0`), Tes C menghasilkan error validasi boolean (bukan konversi diam-diam).
- **Actual Result:** Sesuai expected.
- **Method / Status Awal:** `PUT /tickets/1`
- **Keadaan Database Sebelum:** `is_urgent` = `false`
- **Keadaan Database Sesudah:** Tes A (`true`), Tes B (`false`), Tes C (DB tidak berubah).
- **Status:** PASS
- **Bukti:** Nilai boolean di DB sesuai dan error validasi muncul pada input string.

---

### TC-12: Delete Sukses
- **Prasyarat / ID Record:** Ticket ID: 2 (Status: `open` / `pending`, Memiliki komentar)
- **Payload Penting:** `DELETE /tickets/2`
- **Langkah:** Klik tombol Hapus pada tiket yang berstatus `open` atau `pending`.
- **Expected Result:** Status `303 See Other` (redirect ke daftar tiket), record tiket ID 2 beserta seluruh komentar terkait (*cascade*) terhapus dari database.
- **Actual Result:** Sesuai expected.
- **Method / Status Awal:** `DELETE /tickets/2` -> `303 See Other` -> `GET /tickets`
- **Keadaan Database Sebelum:** `tickets` (n), `comments` (m)
- **Keadaan Database Sesudah:** `tickets` (n-1), `comments` (m - x)
- **Status:** PASS
- **Bukti:** Tiket ID 2 hilang dari daftar tabel web dan database.

---

### TC-13: Delete Ditolak (Business Rule Violation)
- **Prasyarat / ID Record:** Ticket ID: 3 (Status: `closed`)
- **Payload Penting:** `DELETE /tickets/3`
- **Langkah:** Ubah status tiket menjadi `closed`, lalu eksekusi perintah hapus.
- **Expected Result:** Terjadi penolakan (error/exception aturan bisnis), tiket berstatus `closed` tidak boleh dihapus. Total tiket dan komentar tetap.
- **Actual Result:** Sesuai expected.
- **Method / Status Awal:** `DELETE /tickets/3` -> `403 Forbidden` / `302` dengan Flash Error
- **Keadaan Database Sebelum:** Ticket ID 3 (closed)
- **Keadaan Database Sesudah:** Ticket ID 3 tetap ada di database.
- **Status:** PASS
- **Bukti:** Pesan penolakan penghapusan tiket tertutup muncul di layar.

---

### TC-14: Rollback Multi-Tabel (Database Transaction)
- **Prasyarat / ID Record:** Pengujian via Automated Test (`TicketTransactionTest.php`)
- **Payload Penting:** Skenario *failure injection* pada Event/Listener Comment
- **Langkah:** Jalankan perintah `php artisan test --filter TicketTransactionTest`.
- **Expected Result:** Eksekusi `DB::transaction` membatalkan (*rollback*) perintah `create` maupun `update` tiket saat pembuatan komentar disimulasikan gagal.
- **Actual Result:** Sesuai expected.
- **Method / Status Awal:** CLI PHPUnit / Pest -> `PASS`
- **Keadaan Database Sebelum:** Bersih (ruang lingkup pengujian)
- **Keadaan Database Sesudah:** Tidak ada data parsial tertinggal akibat *rollback*.
- **Status:** PASS
- **Bukti:** Terminal output: `PASS Tests\Feature\TicketTransactionTest` (10 assertions).

---

### TC-15: PRG Flow & Anti-Duplikasi Refresh
- **Prasyarat / ID Record:** -
- **Payload Penting:** Payload Create Valid
- **Langkah:** Submit form pembuatan tiket hingga sukses (redirect ke detail), lalu tekan tombol **Refresh / F5** pada browser.
- **Expected Result:** Browser hanya mengulang request `GET` ke halaman detail tiket. Tidak terjadi resubmit form `POST`, serta jumlah `tickets` dan `comments` tidak bertambah.
- **Actual Result:** Sesuai expected.
- **Method / Status Awal:** `POST` -> `303 See Other` -> `GET 200` (saat Refresh: hanya `GET 200`)
- **Keadaan Database Sebelum:** `tickets` (n)
- **Keadaan Database Sesudah:** `tickets` (n+1) [Tetap sama setelah refresh]
- **Status:** PASS
- **Bukti:** Log Network DevTools hanya mencatat request `GET` saat F5 ditekan.

---

### TC-16: Escaping Output (XSS Protection)
- **Prasyarat / ID Record:** -
- **Payload Penting:**
  - `subject`: `<b>Uji Injection</b>`
- **Langkah:** Kirim tiket dengan subject berisi tag HTML `<b>`, lalu buka halaman detail tiket.
- **Expected Result:** Blade rendering `{{ $ticket->subject }}` melakukan escaping otomatis. Teks ditampilkan secara literal sebagai string `<b>Uji Injection</b>` (tidak tebal).
- **Actual Result:** Sesuai expected.
- **Method / Status Awal:** `POST /tickets` -> `GET /tickets/{id}`
- **Keadaan Database Sebelum:** -
- **Keadaan Database Sesudah:** String `<b>Uji Injection</b>` tersimpan mentah di DB.
- **Status:** PASS
- **Bukti:** Tangkapan layar halaman web menampilkan tag HTML secara teks polos.

---

### TC-17: CSRF Protection
- **Prasyarat / ID Record:** -
- **Payload Penting:** Hapus elemen `<input type="hidden" name="_token">` via DevTools.
- **Langkah:** Buka form lokal, hapus input hidden CSRF token dari DOM HTML, lalu klik **Simpan**.
- **Expected Result:** Server menolak request dengan HTTP Status Code `419 Page Expired`, dan database tidak mengalami perubahan.
- **Actual Result:** Sesuai expected.
- **Method / Status Awal:** `POST /tickets` -> `419 Page Expired`
- **Keadaan Database Sebelum:** `tickets` (n)
- **Keadaan Database Sesudah:** `tickets` (n)
- **Status:** PASS
- **Bukti:** Tampilan error bawaan Laravel `419 | Page Expired`.

---

### TC-18: Tipe Data Salah (Array Injection Check)
- **Prasyarat / ID Record:** Sesi dan CSRF valid
- **Payload Penting:**
  - `subject[]`: `"Nilai Array"`
- **Langkah:** Menggunakan DevTools / Postman, ubah nama input `subject` menjadi bentuk array `subject[]` lalu kirim request.
- **Expected Result:** FormRequest/Validation melempar error bahwa field `subject` harus berupa `string`. Aplikasi tidak mengalami error server `500` akibat fungsi `trim()`.
- **Actual Result:** Sesuai expected.
- **Method / Status Awal:** `POST /tickets` -> `302 Found` (atau `422 Unprocessable Content`)
- **Keadaan Database Sebelum:** `tickets` (n)
- **Keadaan Database Sesudah:** `tickets` (n)
- **Status:** PASS
- **Bukti:** Pesan error *"The subject field must be a string"*.