<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with(['category', 'publisher'])->get();
        $categories = Category::all();
        $publishers = Publisher::all();
        $borrowings = Borrowing::with('book')->get();

        return view('books.index', compact('books', 'categories', 'publishers', 'borrowings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'publisher_id' => 'required',
            'isbn' => 'required',
            'stock' => 'required|numeric',
        ]);

        Book::create($validated);

        return redirect()->back()->with('success', 'Buku berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'publisher_id' => 'required',
            'isbn' => 'required',
            'stock' => 'required|numeric',
        ]);

        $book = Book::findOrFail($id);
        $book->update($validated);

        return redirect()->back()->with('success', 'Data buku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Book::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Buku berhasil dihapus!');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate(['name' => 'required']);
        Category::create($validated);
        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function storePublisher(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'city' => 'required'
        ]);
        Publisher::create($validated);
        return redirect()->back()->with('success', 'Penerbit berhasil ditambahkan!');
    }

    public function storeBorrowing(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required',
            'borrower_name' => 'required',
            'borrow_date' => 'required|date',
        ]);

        Borrowing::create($validated);
        return redirect()->back()->with('success', 'Transaksi peminjaman berhasil dicatat!');
    }
}