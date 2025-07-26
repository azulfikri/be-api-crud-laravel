<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //
        $books = Book::with('category')->get(); // Load the category relationship
        return response()->json([
            'data' => $books,
            'message' => 'Berhasil menampilkan daftar buku'
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //
        try {
            //code...
            $validated = $request->validate([
                'judul' => 'required|string|max:255|unique:books,judul',
                'penulis' => 'required|string|max:255',
                'tahun_terbit' => 'required|digits:4|integer|min:1900',
                'jumlah_halaman' => 'nullable|integer|min:0|max:10000',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maks 2MB
                'category_id' => 'required|exists:categories,id',
            ]);
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('books', 'public');
                $validated['image'] = $path;
            }


            $book = Book::create($validated);
            $book->load('category'); // Load the category relationship

            return response()->json([
                'data' => $book,
                'message' => 'Buku berhasil dibuat'
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => 'Gagal membuat buku',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * 
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Book $book)
    {
        //
        try {
            $book->load('category'); // Load the category relationship
            //code...
            return response()->json([
                'data' =>
                $book,
                'message' => 'Berhasil menampilkan buku berdasarkan ID'
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => 'Buku Tidak Ditemukan'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Book $book)
    {
        //
        try {
            //code...
            $validated = $request->validate([
                'judul' => 'required|string|max:255|unique:books,judul,' . $book->id,
                'penulis' => 'required|string|max:255',
                'tahun_terbit' => 'required|digits:4|integer|min:1900',
                'jumlah_halaman' => 'nullable|integer|min:0|max:10000',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'category_id' => 'required|exists:categories,id',
            ]);
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($book->image && Storage::disk('public')->exists($book->image)) {
                    Storage::disk('public')->delete($book->image);
                }
                $path = $request->file('image')->store('books', 'public');
                $validated['image'] = $path;
            }

            $book->update($validated);
            $book->load('category'); // Load the category relationship

            return response()->json([
                'data' => $book,
                'message' => 'Buku berhasil diperbarui'
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => 'Gagal memperbarui buku',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Book $book)
    {
        //
        try {
            //code...
            if ($book->image && Storage::disk('public')->exists($book->image)) {
                Storage::disk('public')->delete($book->image);
            }
            $book->delete();

            return response()->json([
                'message' => 'Buku berhasil dihapus'
            ], 204);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => 'Gagal menghapus buku',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
