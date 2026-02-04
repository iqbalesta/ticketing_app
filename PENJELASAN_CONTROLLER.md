# Penjelasan Kode Admin Controllers

Folder `app/Http/Controllers/Admin` berisi controller-controller yang menangani fitur admin panel.

---

## Struktur Folder

```
app/Http/Controllers/Admin/
├── CategoryController.php   # Kelola kategori event
├── DashboardController.php  # Statistik dashboard
├── EventController.php      # Kelola event (CRUD)
├── HistoriesController.php  # Riwayat transaksi
└── TiketController.php      # Kelola tiket event
```

---

## 1. DashboardController.php

**Fungsi:** Menampilkan dashboard admin dengan statistik

```php
public function index()
{
    $totalEvents = Event::count();
    $totalCategories = Kategori::count();
    $totalOrders = Order::count();
    $totalTickets = Tiket::count();

    return view('admin.dashboard', compact('totalEvents', 'totalCategories', 'totalOrders', 'totalTickets'));
}
```

**Penjelasan:**
- `Event::count()` - Menghitung jumlah total event di database
- `compact()` - Mengirim variabel ke view dengan nama yang sama
- Menampilkan statistik: total event, kategori, order, dan tiket

---

## 2. CategoryController.php

**Fungsi:** Mengelola kategori event (CRUD)

### Method `index()` - Menampilkan semua kategori

```php
public function index()
{
    $categories = Kategori::all();
    return view('pages.admin.category.index', compact('categories'));
}
```

- `Kategori::all()` - Mengambil semua data kategori dari database

### Method `store()` - Menyimpan kategori baru

```php
public function store(Request $request)
{
    $payload = $request->validate([
        'nama' => 'required|string|max:255',
    ]);

    Kategori::create([
        'nama' => $payload['nama'],
    ]);

    return redirect()->route('admin.categories.index')
        ->with('success', 'Kategori berhasil ditambahkan.');
}
```

**Penjelasan Validasi:**

| Rule | Arti |
|------|------|
| `required` | Field wajib diisi |
| `string` | Harus berupa teks |
| `max:255` | Maksimal 255 karakter |

### Method `update()` - Memperbarui kategori

```php
public function update(Request $request, string $id)
{
    $payload = $request->validate([
        'nama' => 'required|string|max:255',
    ]);

    $category = Kategori::findOrFail($id);
    $category->nama = $payload['nama'];
    $category->save();

    return redirect()->route('admin.categories.index')
        ->with('success', 'Kategori berhasil diperbarui.');
}
```

- `findOrFail($id)` - Mencari data, jika tidak ditemukan akan error 404

### Method `destroy()` - Menghapus kategori

```php
public function destroy(string $id)
{
    Kategori::destroy($id);
    return redirect()->route('admin.categories.index')
        ->with('success', 'Kategori berhasil dihapus.');
}
```

---

## 3. EventController.php

**Fungsi:** Mengelola event (CRUD lengkap)

### Method `index()` - Daftar semua event

```php
public function index()
{
    $events = Event::all();
    return view('admin.event.index', compact('events'));
}
```

### Method `create()` - Form tambah event

```php
public function create()
{
    $categories = Kategori::all();
    return view('admin.event.create', compact('categories'));
}
```

- Mengambil semua kategori untuk dropdown pilihan

### Method `store()` - Menyimpan event baru

```php
public function store(Request $request)
{
    $validatedData = $request->validate([
        'judul' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'tanggal_waktu' => 'required|date',
        'lokasi' => 'required|string|max:255',
        'kategori_id' => 'required|exists:kategoris,id',
        'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Handle file upload
    if ($request->hasFile('gambar')) {
        $imageName = time().'.'.$request->gambar->extension();
        $request->gambar->move(public_path('images/events'), $imageName);
        $validatedData['gambar'] = $imageName;
    }

    $validatedData['user_id'] = auth()->user()->id ?? null;

    Event::create($validatedData);

    return redirect()->route('admin.events.index')
        ->with('success', 'Event berhasil ditambahkan.');
}
```

**Penjelasan Validasi:**

| Rule | Arti |
|------|------|
| `required` | Wajib diisi |
| `date` | Format tanggal valid |
| `exists:kategoris,id` | ID harus ada di tabel kategoris |
| `image` | File harus gambar |
| `mimes:jpeg,png...` | Format yang diizinkan |
| `max:2048` | Maksimal ukuran 2MB (2048 KB) |

**Proses Upload Gambar:**
1. Cek apakah ada file gambar di request
2. Buat nama file unik dengan `time()` + extension
3. Pindahkan file ke folder `public/images/events`
4. Simpan nama file ke database

### Method `show()` - Detail event + tiket

```php
public function show(string $id)
{
    $event = Event::findOrFail($id);
    $categories = Kategori::all();
    $tickets = $event->tikets;

    return view('admin.event.show', compact('event', 'categories', 'tickets'));
}
```

- `$event->tikets` - Mengakses relasi One-to-Many (event punya banyak tiket)

### Method `edit()` - Form edit event

```php
public function edit(string $id)
{
    $event = Event::findOrFail($id);
    $categories = Kategori::all();
    return view('admin.event.edit', compact('event', 'categories'));
}
```

### Method `update()` - Memperbarui event

```php
public function update(Request $request, string $id)
{
    try {
        $event = Event::findOrFail($id);

        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_waktu' => 'required|date',
            'lokasi' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle file upload (jika ada gambar baru)
        if ($request->hasFile('gambar')) {
            $imageName = time().'.'.$request->gambar->extension();
            $request->gambar->move(public_path('images/events'), $imageName);
            $validatedData['gambar'] = $imageName;
        }

        $event->update($validatedData);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil diperbarui.');
    } catch (\Exception $e) {
        return redirect()->back()
            ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
}
```

- `nullable` pada gambar = gambar tidak wajib saat update
- `try-catch` = menangkap error jika terjadi kesalahan

### Method `destroy()` - Menghapus event

```php
public function destroy(string $id)
{
    $event = Event::findOrFail($id);
    $event->delete();

    return redirect()->route('admin.events.index')
        ->with('success', 'Event berhasil dihapus.');
}
```

---

## 4. TiketController.php

**Fungsi:** Mengelola tiket untuk setiap event

### Method `store()` - Tambah tiket ke event

```php
public function store(Request $request)
{
    $validatedData = $request->validate([
        'event_id' => 'required|exists:events,id',
        'tipe' => 'required|string|max:255',
        'harga' => 'required|numeric|min:0',
        'stok' => 'required|integer|min:0',
    ]);

    Tiket::create($validatedData);

    return redirect()->route('admin.events.show', $validatedData['event_id'])
        ->with('success', 'Ticket berhasil ditambahkan.');
}
```

**Penjelasan Validasi:**

| Rule | Arti |
|------|------|
| `exists:events,id` | Event ID harus ada di database |
| `numeric` | Harus angka (bisa desimal) |
| `integer` | Harus bilangan bulat |
| `min:0` | Minimal nilai 0 |

### Method `update()` - Update tiket

```php
public function update(Request $request, string $id)
{
    $ticket = Tiket::findOrFail($id);

    $validatedData = $request->validate([
        'tipe' => 'required|string|max:255',
        'harga' => 'required|numeric|min:0',
        'stok' => 'required|integer|min:0',
    ]);

    $ticket->update($validatedData);

    return redirect()->route('admin.events.show', $ticket->event_id)
        ->with('success', 'Ticket berhasil diperbarui.');
}
```

### Method `destroy()` - Hapus tiket

```php
public function destroy(string $id)
{
    $ticket = Tiket::findOrFail($id);
    $eventId = $ticket->event_id; // Simpan ID untuk redirect
    $ticket->delete();

    return redirect()->route('admin.events.show', $eventId)
        ->with('success', 'Ticket berhasil dihapus.');
}
```

---

## 5. HistoriesController.php

**Fungsi:** Melihat riwayat transaksi/order

### Method `index()` - Daftar semua transaksi

```php
public function index()
{
    $histories = Order::latest()->get();
    return view('admin.history.index', compact('histories'));
}
```

- `latest()` = `ORDER BY created_at DESC` (terbaru di atas)

### Method `show()` - Detail transaksi

```php
public function show(string $history)
{
    $order = Order::findOrFail($history);
    return view('admin.history.show', compact('order'));
}
```

---

## Ringkasan CRUD Operations

| Controller | Create | Read | Update | Delete |
|------------|:------:|:----:|:------:|:------:|
| DashboardController | ❌ | ✅ `index()` | ❌ | ❌ |
| CategoryController | ✅ `store()` | ✅ `index()` | ✅ `update()` | ✅ `destroy()` |
| EventController | ✅ `store()` | ✅ `index()`, `show()` | ✅ `update()` | ✅ `destroy()` |
| TiketController | ✅ `store()` | ❌ | ✅ `update()` | ✅ `destroy()` |
| HistoriesController | ❌ | ✅ `index()`, `show()` | ❌ | ❌ |

---

## Pattern Umum yang Digunakan

### 1. Validasi Input

```php
$validated = $request->validate([
    'field' => 'required|string|max:255',
]);
```

### 2. Cari Data dengan Error Handling

```php
$model = Model::findOrFail($id); // 404 jika tidak ditemukan
```

### 3. CRUD Operations

```php
// Create
Model::create($validated);

// Read
Model::all();
Model::find($id);

// Update
$model->update($validated);

// Delete
$model->delete();
```

### 4. Redirect dengan Flash Message

```php
return redirect()->route('nama.route')
    ->with('success', 'Pesan sukses');
```

### 5. Upload File

```php
if ($request->hasFile('gambar')) {
    $imageName = time().'.'.$request->gambar->extension();
    $request->gambar->move(public_path('folder'), $imageName);
}
```

---

## Alur Request Admin

```
Browser Request
      │
      ▼
┌─────────────────┐
│   Route (web.php)   │
└─────────────────┘
      │
      ▼
┌─────────────────┐
│ AdminMiddleware │  ← Cek apakah user adalah admin
└─────────────────┘
      │
      ▼
┌─────────────────┐
│   Controller    │  ← Proses logic bisnis
└─────────────────┘
      │
      ▼
┌─────────────────┐
│     Model       │  ← Interaksi dengan database
└─────────────────┘
      │
      ▼
┌─────────────────┐
│     View        │  ← Render tampilan HTML
└─────────────────┘
      │
      ▼
Browser Response
```
