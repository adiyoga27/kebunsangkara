# 🌳 Kebun Sangkara

Website **[kebunsangkara.com](https://kebunsangkara.com)** — penjualan buah segar langsung dari kebun di Bali (**durian, alpukat, dan manggis**), dioptimasi untuk SEO Google, dengan pemesanan langsung via **WhatsApp**, panel admin untuk mengelola konten, dan **log aktivitas pengguna**.

Dibangun dengan Laravel 13, Blade, dan Tailwind CSS 4 — tanpa framework JavaScript tambahan.

---

## ✨ Fitur

### Website publik
- **Beranda** dengan hero foto kebun beranimasi, kategori buah, cerita kebun, daftar harga, cara pesan, galeri kebun, testimoni, artikel, dan FAQ.
- **Produk & Harga** (`/produk`), halaman per jenis buah (`/buah/durian`, `/buah/alpukat`, `/buah/manggis`), dan detail produk (`/produk/{slug}`).
- **Artikel / blog** (`/artikel`) dengan konten Markdown, mendukung jadwal terbit.
- **Tentang Kami** dan **Kontak** (dengan peta lokasi).
- **Tombol WhatsApp** di setiap halaman dengan pesan otomatis berisi nama produk, harga, dan link. Setiap klik tercatat sebagai calon pembeli.
- Animasi modern (scroll reveal, kartu 3D, parallax, marquee) yang otomatis dimatikan untuk pengguna dengan pengaturan *reduce motion*.

### SEO
- Title, meta description, canonical, Open Graph & Twitter Card di setiap halaman.
- Data terstruktur Schema.org (JSON-LD): `Store` (Local Business), `Product` + `Offer`, `FAQPage`, `BlogPosting`, `BreadcrumbList`, `ItemList`.
- `sitemap.xml` otomatis dan `robots.txt` dinamis (memblokir crawler di luar mode production).
- Meta lokasi Bali (`geo.region`, `geo.position`), bahasa `id-ID`.
- Font di-host sendiri, gambar *lazy-load*, HTML semantik dengan satu `<h1>` per halaman.
- Kode verifikasi Google Search Console dan Google Analytics bisa diisi dari admin.

### Panel admin (`/admin`)
| Menu | Editor | Administrator |
| --- | :---: | :---: |
| Dashboard (statistik & grafik klik WhatsApp) | ✅ | ✅ |
| Produk (kategori buah, harga, stok, foto, SEO) | ✅ | ✅ |
| Kategori buah (tambah, ubah, hapus) | ✅ | ✅ |
| Artikel | ✅ | ✅ |
| Testimoni & FAQ | ✅ | ✅ |
| Klik WhatsApp (daftar calon pembeli) | — | ✅ |
| Log Aktivitas | — | ✅ |
| Pengguna | — | ✅ |
| Pengaturan website | — | ✅ |

### Log aktivitas pengguna
- Mencatat **login, logout, login gagal** (beserta email yang dicoba).
- Mencatat setiap **tambah, ubah, hapus** data, lengkap dengan nilai lama dan baru.
- Menyimpan **IP address dan perangkat** (user agent). Password tidak pernah disimpan di log.
- Bisa difilter berdasarkan pengguna, aksi, tanggal, dan kata kunci.
- Login dibatasi 5 percobaan per menit; akun yang dinonaktifkan langsung dikeluarkan dari sesi.

---

## 🚀 Instalasi

**Kebutuhan:** PHP 8.3+, Composer, Node.js 20+, dan database (SQLite/MySQL).

```bash
git clone https://github.com/adiyoga27/kebunsangkara.git
cd kebunsangkara

composer install
cp .env.example .env
php artisan key:generate

php artisan migrate --seed
php artisan storage:link

npm install
npm run build
```

Perintah `migrate --seed` membuat **akun admin** (`admin@durianbali.test`) dengan **password acak yang ditampilkan di terminal** — catat password tersebut, lalu segera ganti lewat menu **Profil Saya**.

Seeder juga mengisi contoh produk, artikel, dan FAQ. Contoh testimoni sengaja **tidak ditampilkan** — ganti dengan ulasan asli dari pelanggan.

### Menjalankan secara lokal

```bash
composer run dev
```

Buka http://localhost:8000 untuk website dan http://localhost:8000/admin untuk panel admin.

---

## ⚙️ Konfigurasi setelah instalasi

Masuk ke **Admin → Pengaturan** dan isi:

1. **Nomor WhatsApp** — semua tombol pesan diarahkan ke nomor ini.
2. **Alamat, kota, kode pos, koordinat** — dipakai untuk SEO lokal dan harus **sama persis** dengan Google Business Profile.
3. **Area pengiriman, jam buka, media sosial.**
4. **Meta title & description beranda.**
5. **Kode verifikasi Google Search Console** dan **Google Analytics ID** (opsional).

Lalu di **Admin → Produk**, perbarui harga dan **unggah foto buah asli** dari kebun Anda. Produk tanpa foto otomatis memakai foto bawaan kategorinya (`public/images/kebun/`).

---

## 🌐 Deploy ke production

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan storage:link
npm ci && npm run build
php artisan optimize
```

Pastikan di `.env`:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://kebunsangkara.com
APP_TIMEZONE=Asia/Makassar
```

> `robots.txt` hanya mengizinkan Google mengindeks website saat `APP_ENV=production`.

Setelah online, daftarkan `https://kebunsangkara.com/sitemap.xml` di **Google Search Console**.

---

## 📈 Tips agar website naik di Google

Kode website sudah dioptimasi, tetapi peringkat juga ditentukan faktor di luar kode:

1. **Google Business Profile** — daftarkan toko dengan nama, alamat, dan nomor yang sama dengan website.
2. **Foto asli** buah dan kebun Anda. Foto bawaan di `public/images/kebun/` sebaiknya diganti dengan foto milik sendiri — foto dari internet umumnya berhak cipta.
3. **Artikel rutin** 1–2 kali per bulan dengan kata kunci yang dicari orang (mis. "harga durian Bali hari ini", "alpukat mentega Bali", "jual manggis Tabanan").
4. **Ulasan Google Maps** dari pelanggan.
5. **Backlink** dari blog wisata, kuliner, atau direktori bisnis Bali.

---

## 🧪 Testing

```bash
php artisan test --compact
```

Format kode PHP:

```bash
vendor/bin/pint
```

---

## 🗂️ Struktur penting

```
app/
├── Enums/                  # ProductType, UserRole, ActivityAction
├── Http/Controllers/Site/  # Halaman publik, sitemap, redirect WhatsApp
├── Http/Controllers/Admin/ # Panel admin
├── Listeners/              # Pencatat login/logout/login gagal
├── Models/Concerns/        # RecordsActivity (log tambah/ubah/hapus otomatis)
└── Support/                # ActivityLogger, SiteSettings
resources/
├── css/app.css             # Tema Tailwind & animasi
├── js/app.js               # Scroll reveal, tilt 3D, parallax, counter
└── views/
    ├── site/               # Halaman publik
    ├── admin/              # Halaman admin
    └── components/         # Layout & komponen Blade
```
