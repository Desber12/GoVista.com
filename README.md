# Website Travel dengan PHP, MySQL, dan CSS

Website travel dengan sistem role admin dan user yang dilengkapi fitur CRUD lengkap.

## Fitur Utama

### Untuk Admin:
- Dashboard dengan statistik lengkap
- Kelola destinasi wisata (Create, Read, Update, Delete)
- Kelola kategori destinasi (Create, Read, Update, Delete)
- Kelola booking dari user (Read, Update status)
- Kelola user (Read)

### Untuk User:
- Registrasi dan login
- Browse destinasi wisata dengan filter kategori
- Booking destinasi wisata
- Kelola booking pribadi
- Dashboard user dengan statistik booking

### Fitur Umum:
- Sistem autentikasi dengan role-based access
- Interface responsif untuk desktop dan mobile
- Upload gambar untuk destinasi
- Search dan filter destinasi
- Status tracking untuk booking

## Struktur Database

### Tabel Users
- id (Primary Key)
- username (Unique)
- email (Unique)
- password (Hashed)
- full_name
- phone
- role (admin/user)
- created_at, updated_at

### Tabel Categories
- id (Primary Key)
- name
- description
- created_at

### Tabel Destinations
- id (Primary Key)
- name
- description
- location
- price
- duration
- max_capacity
- category_id (Foreign Key)
- image
- status (active/inactive)
- created_at, updated_at

### Tabel Bookings
- id (Primary Key)
- user_id (Foreign Key)
- destination_id (Foreign Key)
- booking_date
- travel_date
- participants
- total_price
- status (pending/confirmed/cancelled/completed)
- special_requests
- created_at, updated_at

## Instalasi

1. **Setup Database:**
   ```sql
   -- Import file database.sql ke MySQL
   mysql -u root -p < database.sql
   ```

2. **Konfigurasi Database:**
   - Edit file `includes/config.php`
   - Sesuaikan pengaturan database (host, username, password, database name)

3. **Setup Web Server:**
   - Pastikan PHP dan MySQL sudah terinstall
   - Letakkan folder website di document root (htdocs/www)
   - Akses melalui browser

4. **Login Default:**
   - **Admin:** username: `admin`, password: `password`
   - **User:** Daftar melalui halaman register

## Struktur File

```
travel-website/
├── admin/                  # Panel admin
│   ├── dashboard.php      # Dashboard admin
│   ├── destinations.php   # Kelola destinasi
│   ├── categories.php     # Kelola kategori
│   ├── bookings.php       # Kelola booking
│   └── users.php          # Kelola user
├── user/                   # Panel user
│   ├── dashboard.php      # Dashboard user
│   ├── destinations.php   # Browse destinasi
│   ├── booking.php        # Form booking
│   ├── bookings.php       # Daftar booking user
│   └── booking-success.php # Konfirmasi booking
├── includes/               # File konfigurasi
│   ├── config.php         # Konfigurasi database
│   └── functions.php      # Helper functions
├── assets/                 # Asset website
│   ├── css/
│   │   └── style.css      # Stylesheet utama
│   ├── js/                # JavaScript files
│   └── images/            # Gambar upload
├── index.php              # Halaman utama
├── login.php              # Halaman login
├── register.php           # Halaman registrasi
├── logout.php             # Logout handler
└── database.sql           # Struktur database
```

## Teknologi yang Digunakan

- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+
- **Frontend:** HTML5, CSS3, JavaScript
- **Styling:** Custom CSS dengan Flexbox dan Grid
- **Security:** Password hashing, SQL prepared statements, Input sanitization

## Fitur Keamanan

1. **Password Hashing:** Menggunakan `password_hash()` dan `password_verify()`
2. **SQL Injection Prevention:** Menggunakan prepared statements
3. **XSS Prevention:** Input sanitization dengan `htmlspecialchars()`
4. **Session Management:** Proper session handling
5. **Role-based Access Control:** Pembatasan akses berdasarkan role

## Cara Penggunaan

### Admin:
1. Login dengan akun admin
2. Kelola kategori destinasi di menu "Kelola Kategori"
3. Tambah destinasi wisata di menu "Kelola Destinasi"
4. Monitor dan update status booking di menu "Kelola Booking"

### User:
1. Daftar akun baru atau login
2. Browse destinasi di halaman "Destinasi"
3. Pilih destinasi dan lakukan booking
4. Monitor status booking di "Booking Saya"

## Customization

### Menambah Field Baru:
1. Update struktur database
2. Modifikasi form input
3. Update query INSERT/UPDATE
4. Sesuaikan tampilan

### Mengubah Styling:
- Edit file `assets/css/style.css`
- Gunakan CSS variables untuk konsistensi warna
- Responsive design sudah diimplementasi

## Troubleshooting

### Error Database Connection:
- Periksa konfigurasi di `includes/config.php`
- Pastikan MySQL service berjalan
- Cek username/password database

### Upload Gambar Gagal:
- Periksa permission folder `assets/images/`
- Pastikan ukuran file tidak melebihi limit
- Cek format file yang diizinkan

### Session Issues:
- Pastikan `session_start()` dipanggil
- Periksa konfigurasi session di PHP

## Pengembangan Lanjutan

Fitur yang bisa ditambahkan:
- Payment gateway integration
- Email notification system
- Review dan rating system
- Multi-language support
- Advanced reporting
- Mobile app API
- Social media integration

## Lisensi

Project ini dibuat untuk keperluan pembelajaran dan dapat digunakan secara bebas.

## Kontak

Untuk pertanyaan atau dukungan, silakan hubungi developer.

