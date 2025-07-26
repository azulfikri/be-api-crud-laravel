<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     tags={"Categories"},
     *     summary="Get all categories",
     *     @OA\Response(
     *         response=200,
     *         description="List of categories"
     *     )
     * )
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

    public function create()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/api/categories",
     *     tags={"Categories"},
     *     summary="Create a new category",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nama"},
     *             @OA\Property(property="nama", type="string", example="Teknologi")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Category created"),
     *     @OA\Response(response=400, description="Invalid data")
     * )
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
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     tags={"Categories"},
     *     summary="Get category detail by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Category data"),
     *     @OA\Response(response=404, description="Category not found")
     * )
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
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     tags={"Categories"},
     *     summary="Update a category",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama", type="string", example="Fiksi")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Category updated"),
     *     @OA\Response(response=404, description="Category not found")
     * )
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
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     tags={"Categories"},
     *     summary="Delete a category by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Category deleted"),
     *     @OA\Response(response=404, description="Category not found")
     * )
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
