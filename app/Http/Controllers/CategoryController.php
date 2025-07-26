<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // $categories = Category::all();
        $categories = Category::all();
        return response()->json([
            'data' => $categories,
            'message' => 'Berhasil menampilkan kategori'
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
     */
    public function store(Request $request)
    {
        //
        try {
            //code...
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $category = Category::create($validated);
            return response()->json([
                'data' => $category,
                'message' => 'Berhasil membuat kategori'
            ], 201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => 'Gagal membuat kategori',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
        try {
            //code...
            $category->load('books');
            return response()->json([
                'data' => $category,
                'message' => 'Berhasil menampilkan ketegori berdasarkan ID'
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => 'Kategori Tidak Ditemukan'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
        try {
            //code...
            $validated = $request->validate([
                'name' => 'required|string|unique:categories,name,' . $category->id .  '|max:255',
            ]);
            $category->update($validated);
            return response()->json([
                'data' => $category,
                'message' => 'Kategori berhasil diperbarui'
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => 'Gagal memperbarui kategori',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
        try {
            //code...
            $category->delete();
            return response()->json([
                'message' => 'Berhasil menghapus kategori'
            ], 204);
        } catch (\Throwable $e) {
            //throw $th;
            return response()->json([
                'message' => 'Gagal menghapus kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
