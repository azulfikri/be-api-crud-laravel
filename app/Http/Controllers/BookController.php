<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Tag(
 *     name="Books",
 *     description="API Endpoints untuk manajemen buku"
 * )
 */

class BookController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/books",
     *     tags={"Books"},
     *     summary="Get all books",
     *     @OA\Response(
     *         response=200,
     *         description="List of books"
     *     )
     * )
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
     * @OA\Post(
     *     path="/api/books",
     *     tags={"Books"},
     *     summary="Create a new book",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"judul", "penulis", "tahun_terbit", "category_id"},
     *             @OA\Property(property="judul", type="string", example="Laravel Handbook"),
     *             @OA\Property(property="penulis", type="string", example="Taylor Otwell"),
     *             @OA\Property(property="tahun_terbit", type="integer", example=2024),
     *             @OA\Property(property="jumlah_halaman", type="integer", example=900),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *         )
     *     ),
     *     @OA\Response(response=201, description="Book created"),
     *     @OA\Response(response=400, description="Invalid data")
     * )
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
     * @OA\Get(
     *     path="/api/books/{id}",
     *     tags={"Books"},
     *     summary="Get book detail by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Book ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Book data"),
     *     @OA\Response(response=404, description="Book not found")
     * )
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
     * @OA\Put(
     *     path="/api/books/{id}",
     *     tags={"Books"},
     *     summary="Update a book",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Book ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="judul", type="string"),
     *             @OA\Property(property="penulis", type="string"),
     *             @OA\Property(property="tahun_terbit", type="integer"),
     *             @OA\Property(property="jumlah_halaman", type="integer"),
     *             @OA\Property(property="category_id", type="integer"),
     *         )
     *     ),
     *     @OA\Response(response=200, description="Book updated"),
     *     @OA\Response(response=404, description="Book not found")
     * )
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
     * @OA\Delete(
     *     path="/api/books/{id}",
     *     tags={"Books"},
     *     summary="Delete a book by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Book ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Book deleted"),
     *     @OA\Response(response=404, description="Book not found")
     * )
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
