<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Perpustakaan Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 p-8 font-sans">

    <div class="max-w-6xl mx-auto space-y-8">
        <!-- Header -->
        <header class="text-center">
            <h1 class="text-4xl font-extrabold text-emerald-700 tracking-wide">📚 Sistem Informasi Perpustakaan</h1>
        </header>

        @if(session('success'))
            <div class="p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-800 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Grid Tambah Kategori & Penerbit -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Form Kategori -->
            <div class="bg-white p-5 rounded-xl shadow border border-slate-200">
                <h3 class="font-bold text-lg text-emerald-800 mb-3">+ Master Kategori</h3>
                <form action="{{ route('categories.store') }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="text" name="name" placeholder="Nama Kategori" class="w-full border p-2 rounded focus:ring-2 focus:ring-emerald-500 outline-none" required>
                    <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-4 py-2 rounded">Simpan</button>
                </form>
            </div>

            <!-- Form Penerbit -->
            <div class="bg-white p-5 rounded-xl shadow border border-slate-200">
                <h3 class="font-bold text-lg text-emerald-800 mb-3">+ Master Penerbit</h3>
                <form action="{{ route('publishers.store') }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="text" name="name" placeholder="Nama Penerbit" class="w-full border p-2 rounded focus:ring-2 focus:ring-emerald-500 outline-none" required>
                    <input type="text" name="city" placeholder="Kota" class="w-full border p-2 rounded focus:ring-2 focus:ring-emerald-500 outline-none" required>
                    <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-4 py-2 rounded">Simpan</button>
                </form>
            </div>
        </div>

        <!-- Form Tambah Buku -->
        <section class="bg-white p-6 rounded-xl shadow border border-slate-200">
            <h2 class="text-xl font-bold text-emerald-900 mb-4">+ Tambah Buku Baru</h2>
            <form action="{{ route('books.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @csrf
                <input type="text" name="title" placeholder="Judul Buku" class="border p-2.5 rounded focus:ring-2 focus:ring-emerald-500 outline-none" required>
                
                <select name="category_id" class="border p-2.5 rounded focus:ring-2 focus:ring-emerald-500 outline-none" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>

                <select name="publisher_id" class="border p-2.5 rounded focus:ring-2 focus:ring-emerald-500 outline-none" required>
                    <option value="">-- Pilih Penerbit --</option>
                    @foreach($publishers as $pub)
                        <option value="{{ $pub->id }}">{{ $pub->name }} ({{ $pub->city }})</option>
                    @endforeach
                </select>

                <input type="text" name="isbn" placeholder="Nomor ISBN" class="border p-2.5 rounded focus:ring-2 focus:ring-emerald-500 outline-none" required>
                <input type="number" name="stock" placeholder="Jumlah Stok" class="border p-2.5 rounded focus:ring-2 focus:ring-emerald-500 outline-none" required>

                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-4 rounded shadow">Simpan Buku</button>
            </form>
        </section>

        <!-- Tabel Utama Katalog Buku -->
        <section class="bg-white p-6 rounded-xl shadow border border-slate-200">
            <h2 class="text-xl font-bold text-emerald-900 mb-4">📖 Katalog Buku Perpustakaan</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-emerald-100 text-emerald-900 border-b border-emerald-200">
                            <th class="p-3">Judul Buku</th>
                            <th class="p-3">Kategori</th>
                            <th class="p-3">Penerbit</th>
                            <th class="p-3">ISBN</th>
                            <th class="p-3">Stok</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($books as $book)
                            <tr class="border-b hover:bg-slate-50">
                                <td class="p-3 font-semibold">{{ $book->title }}</td>
                                <td class="p-3">{{ $book->category->name ?? '-' }}</td>
                                <td class="p-3">{{ $book->publisher->name ?? '-' }}</td>
                                <td class="p-3 font-mono text-sm">{{ $book->isbn }}</td>
                                <td class="p-3">{{ $book->stock }} pcs</td>
                                <td class="p-3 text-center flex justify-center space-x-2">
                                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Hapus buku ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-rose-500 hover:bg-rose-600 text-white px-3 py-1 rounded text-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-4 text-slate-500">Belum ada buku di database. Silakan isi master Kategori, Penerbit, lalu Tambah Buku.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Form & Tabel Peminjaman (Bisa Edit Peminjam) -->
        <section class="bg-white p-6 rounded-xl shadow border border-slate-200 space-y-4">
            <h2 class="text-xl font-bold text-emerald-900">📝 Transaksi Peminjaman Buku</h2>
            
            <form action="{{ route('borrowings.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <select name="book_id" class="border p-2.5 rounded outline-none" required>
                    <option value="">-- Pilih Buku --</option>
                    @foreach($books as $b)
                        <option value="{{ $b->id }}">{{ $b->title }}</option>
                    @endforeach
                </select>
                <input type="text" name="borrower_name" placeholder="Nama Peminjam / Mahasiswa" class="border p-2.5 rounded outline-none" required>
                <input type="date" name="borrow_date" class="border p-2.5 rounded outline-none" required>
                <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white font-semibold py-2.5 px-4 rounded">Catat Pinjam</button>
            </form>

            <div class="overflow-x-auto pt-2">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 border-b">
                            <th class="p-2">Peminjam</th>
                            <th class="p-2">Buku Dipinjam</th>
                            <th class="p-2">Tanggal Pinjam</th>
                            <th class="p-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrowings as $bor)
                            <tr class="border-b text-sm">
                                <td class="p-2 font-medium">{{ $bor->borrower_name }}</td>
                                <td class="p-2">{{ $bor->book->title ?? '-' }}</td>
                                <td class="p-2">{{ $bor->borrow_date }}</td>
                                <td class="p-2 text-center">
                                    <button onclick="openEditModal('{{ $bor->id }}', '{{ $bor->borrower_name }}', '{{ $bor->book_id }}', '{{ $bor->borrow_date }}')" class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1 rounded text-xs font-semibold">Edit Peminjam</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center p-3 text-slate-400 text-sm">Belum ada transaksi peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <!-- Modal Edit Peminjam -->
    <div id="editModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-md w-full p-6 space-y-4 shadow-2xl">
            <h3 class="text-lg font-bold text-slate-800">Edit Data Peminjam</h3>
            <form id="editForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Peminjam</label>
                    <input type="text" id="edit_borrower_name" name="borrower_name" class="w-full border p-2 rounded outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Buku Dipinjam</label>
                    <select id="edit_book_id" name="book_id" class="w-full border p-2 rounded outline-none" required>
                        @foreach($books as $b)
                            <option value="{{ $b->id }}">{{ $b->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal Pinjam</label>
                    <input type="date" id="edit_borrow_date" name="borrow_date" class="w-full border p-2 rounded outline-none" required>
                </div>
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 rounded text-sm font-medium">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-sm font-medium">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, name, bookId, date) {
            document.getElementById('editForm').action = '/borrowings/' + id;
            document.getElementById('edit_borrower_name').value = name;
            document.getElementById('edit_book_id').value = bookId;
            document.getElementById('edit_borrow_date').value = date;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</body>
</html>