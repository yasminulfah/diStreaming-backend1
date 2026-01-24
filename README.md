# diStreaming - API Backend

Repository ini berisi sistem Backend (API) untuk platform diStreaming Movie. Dibangun menggunakan **Laravel 11**, sistem ini menyediakan layanan data untuk aplikasi frontend React.

## 🚀 Fitur Utama

- **Autentikasi & Otorisasi**:
    - Registrasi dan login pengguna menggunakan **Laravel Sanctum**.
    - **Role-Based Access Control (RBAC)**: Perbedaan hak akses antara `Admin` dan `User`.
    - Perlindungan API berbasis Token (Bearer Token).

- **Fitur Admin**:
    - Operasi CRUD (Create, Read, Update, Delete) untuk Film.
    - Manajemen User
    - Relasi film dengan banyak Aktor sekaligus.
    - Mendukung penyimpanan URL Poster

- **Fitur Pengguna**:
    - **Riwayat Tontonan (Watchlist)**: Mencatat film yang ditonton.
    - **Ulasan (Reviews)**: Pengguna dapat memberikan rating dan komentar pada film.
    - **Paket Berlangganan**: Tersedia paket basic, standard, dan premium

## 🛠️ Tech Stack
- **Framework**: [Laravel](https://laravel.com/) (PHP)
- **Database**: MySQL 
- **Autentikasi**: Laravel Sanctum
- **Standar API**: RESTful JSON API

## 🗄️ Struktur Database
Relasi tabel-tabel yang ada di database sebagai berikut:
- **Movies:** Judul, deskripsi, poster, rating, dan tahun rilis.
- **Categories:** Pengelompokan film (1-to-many).
- **Directors:** Informasi sutradara (1-to-many).
- **Actors:** Daftar pemeran film (many-to-many).
- **Watchlist:** Menyimpan daftar film favorit user.

## ⚙️ Instalasi Backend

1. **Clone Repository**
   ```bash
   git clone [https://github.com/yasminulfah/diStreaming-backend.git](https://github.com/yasminulfah/diStreaming-backend.git)
   cd diStreaming-backend

2. **Install Dependencies**
    ```bash
    composer install
    ```

3. **Environment Configuration**
    Open `.env` and set your database connection:
    ```ini
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=distreaming_db
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4. **Generate Application Key**
    ```bash
    php artisan key:generate
    ```

5. **Run Migrations**
    ```bash
    php artisan migrate
    ```

6. **Run the Server**
    ```bash
    php artisan serve
    ```

## 🔗 Links

* **Repository Backend**: [https://github.com/yasminulfah/diStreaming-backend1.git]
* **Backend Deployment**: [https://distreaming-backend1.railway.internal]
* **Repository Frontend**: [https://github.com/yasminulfah/diStreaming-frontend.git]
* **Frontend Deployment**: [https://distreaming-movie-website.vercel.app/]