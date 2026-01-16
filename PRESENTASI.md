# Teks Presentasi Aplikasi Ticketing

---

## 1. Demo Aplikasi: Alur Admin (Kelola Event)

### Slide: Alur Admin dalam Mengelola Event

**Narasi:**

"Selamat pagi/siang, saya akan mempresentasikan aplikasi ticketing yang telah kami bangun menggunakan Laravel.

**Langkah-langkah Admin dalam Kelola Event:**

1. **Login sebagai Admin** - Admin mengakses `/login` dan masuk dengan kredensial admin
2. **Dashboard Admin** - Setelah login, admin diarahkan ke `/admin/events` untuk melihat daftar semua event
3. **Tambah Event Baru** - Admin klik tombol 'Tambah Event', mengisi form (judul, deskripsi, tanggal, lokasi, kategori, gambar)
4. **Kelola Tiket** - Di halaman detail event, admin dapat menambah berbagai tipe tiket (Reguler, VIP, Tribun, dll) dengan harga dan stok masing-masing
5. **Edit & Hapus** - Admin dapat mengubah atau menghapus event dan tiket yang sudah ada
6. **Monitoring Transaksi** - Admin dapat melihat riwayat transaksi semua pembeli melalui menu 'Riwayat Transaksi'"

---

## 2. Penjelasan Alur Logika (MVC)

### Slide: Route - Menangani Request Pembelian

**Narasi:**

"Aplikasi ini menggunakan arsitektur MVC. Mari kita lihat bagaimana **Route** menangani request pembelian tiket."

**Kode Route (`routes/web.php`):**

```php
// Route untuk checkout/pembelian tiket
Route::post('/checkout', [OrderController::class, 'store'])
    ->name('checkout.store')
    ->middleware('auth');
```

**Penjelasan:**
- Route menggunakan method `POST` untuk menerima data pembelian
- Middleware `auth` memastikan hanya user yang sudah login yang bisa membeli
- Request diteruskan ke `OrderController` method `store()`

---

### Slide: Controller - Validasi Kuota dan Simpan Data

**Narasi:**

"Selanjutnya, **Controller** bertugas memvalidasi data dan memproses pembelian."

**Kode Controller (`app/Http/Controllers/User/OrderController.php`):**

```php
public function store(Request $request)
{
    // 1. Validasi input
    $validated = $request->validate([
        'tiket_id' => 'required|exists:tikets,id',
        'jumlah' => 'required|integer|min:1',
    ]);

    // 2. Ambil data tiket
    $tiket = Tiket::findOrFail($validated['tiket_id']);

    // 3. Cek ketersediaan stok (validasi kuota)
    if ($tiket->stok < $validated['jumlah']) {
        return back()->with('error', 'Stok tiket tidak mencukupi');
    }

    // 4. Gunakan Database Transaction untuk konsistensi data
    DB::transaction(function () use ($tiket, $validated) {
        // Kurangi stok tiket
        $tiket->decrement('stok', $validated['jumlah']);

        // Buat record order baru
        Order::create([
            'user_id' => auth()->id(),
            'tiket_id' => $tiket->id,
            'jumlah' => $validated['jumlah'],
            'total_harga' => $tiket->harga * $validated['jumlah'],
            'status' => 'pending',
        ]);
    });

    return redirect()->route('orders.index')
        ->with('success', 'Pembelian tiket berhasil!');
}
```

**Penjelasan:**
- **Validasi Input**: Memastikan tiket_id valid dan jumlah minimal 1
- **Cek Stok**: Memvalidasi apakah stok mencukupi sebelum proses
- **Database Transaction**: Menjamin atomicity - jika satu proses gagal, semua dibatalkan
- **Decrement Stok**: Mengurangi stok tiket secara otomatis

---

### Slide: Model - Interaksi Eloquent (Relasi)

**Narasi:**

"**Model** dalam Laravel menggunakan Eloquent ORM untuk mendefinisikan relasi antar tabel."

**Model Event (`app/Models/Event.php`):**

```php
class Event extends Model
{
    protected $fillable = ['judul', 'deskripsi', 'tanggal_waktu', 
                           'lokasi', 'gambar', 'kategori_id'];

    // Relasi: Event belongs to Kategori (Many-to-One)
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    // Relasi: Event has many Tiket (One-to-Many)
    public function tikets()
    {
        return $this->hasMany(Tiket::class);
    }
}
```

**Model Tiket (`app/Models/Tiket.php`):**

```php
class Tiket extends Model
{
    protected $fillable = ['event_id', 'tipe', 'harga', 'stok'];

    // Relasi: Tiket belongs to Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Relasi: Tiket has many Order
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
```

**Diagram Relasi:**

```
Kategori (1) ──────< Event (N)
                        │
                        │ (1)
                        │
                        ▼
                    Tiket (N)
                        │
                        │ (1)
                        │
                        ▼
                    Order (N) >────── User (1)
```

**Penjelasan:**
- **Event-Kategori**: Satu kategori memiliki banyak event (One-to-Many)
- **Event-Tiket**: Satu event memiliki banyak tiket (One-to-Many)
- Eloquent memudahkan akses relasi: `$event->kategori->nama`, `$event->tikets`

---

## 3. Reasoning: Alasan Penggunaan Pola Kode

### Slide: Alasan Arsitektur & Pola Kode

**Narasi:**

"Berikut alasan kami menggunakan pola kode tertentu untuk menjamin stabilitas sistem."

| Pola/Teknik | Alasan | Manfaat |
|-------------|--------|---------|
| **MVC Architecture** | Memisahkan logika bisnis, tampilan, dan routing | Kode lebih terorganisir, mudah di-maintain dan di-test |
| **Database Transaction** | Mencegah data inconsistency saat proses gagal di tengah jalan | Jika pengurangan stok berhasil tapi create order gagal, semua akan di-rollback |
| **Eloquent Relationships** | Menghindari query manual yang rawan error | Relasi terdefinisi jelas, mudah diakses dengan sintaks sederhana |
| **Middleware Auth & Admin** | Mengamankan route berdasarkan role | User biasa tidak bisa akses halaman admin, dan sebaliknya |
| **Request Validation** | Memvalidasi input sebelum proses | Mencegah data invalid masuk ke database |
| **Soft Deletes (opsional)** | Data tidak benar-benar dihapus | Memungkinkan recovery data dan audit trail |

**Contoh Pentingnya Transaction:**

```php
// TANPA Transaction (Berbahaya!)
$tiket->decrement('stok', $jumlah);  // ✓ Berhasil
Order::create([...]);                 // ✗ Gagal (misal: koneksi putus)
// Stok sudah berkurang tapi order tidak tercatat! 😱

// DENGAN Transaction (Aman!)
DB::transaction(function () {
    $tiket->decrement('stok', $jumlah);
    Order::create([...]);
});
// Jika salah satu gagal, semua di-rollback ✓
```

---

### Slide: Kesimpulan

**Narasi:**

"Dengan menggunakan arsitektur MVC Laravel dan pola-pola yang telah dijelaskan, aplikasi ticketing ini memiliki:

1. ✅ **Keamanan** - Middleware melindungi akses berdasarkan role
2. ✅ **Konsistensi Data** - Database Transaction mencegah data corrupt
3. ✅ **Maintainability** - Kode terstruktur dengan MVC, mudah dikembangkan
4. ✅ **Scalability** - Eloquent relationships memudahkan penambahan fitur
5. ✅ **User Experience** - Validasi yang baik memberikan feedback yang jelas

Demikian presentasi dari kami. Terima kasih."

---

## Referensi Kode Aplikasi

### Struktur Folder Utama

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── EventController.php
│   │   │   ├── KategoriController.php
│   │   │   ├── TiketController.php
│   │   │   └── HistoriesController.php
│   │   └── User/
│   │       ├── HomeController.php
│   │       ├── EventController.php
│   │       └── OrderController.php
│   └── Middleware/
│       └── AdminMiddleware.php
├── Models/
│   ├── Event.php
│   ├── Kategori.php
│   ├── Tiket.php
│   ├── Order.php
│   └── User.php
resources/
├── views/
│   ├── admin/
│   │   ├── event/
│   │   ├── kategori/
│   │   └── history/
│   ├── events/
│   ├── orders/
│   └── home.blade.php
routes/
└── web.php
```

### URL Penting

| URL | Deskripsi | Role |
|-----|-----------|------|
| `/` | Homepage dengan slideshow event | Public |
| `/login` | Halaman login | Guest |
| `/register` | Halaman registrasi | Guest |
| `/events/{id}` | Detail event & pembelian tiket | Public |
| `/orders` | Riwayat pembelian user | User |
| `/admin/events` | Kelola event | Admin |
| `/admin/categories` | Kelola kategori | Admin |
| `/admin/histories` | Riwayat semua transaksi | Admin |
