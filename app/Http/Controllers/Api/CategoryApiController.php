<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories, 200);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:255|unique:categories,cat_title|regex:/^[A-Za-z0-9\s]+$/',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Create a new category
        $category = new Category();
        $category->cat_title = $request->category;
        $category->save();

        return response()->json(['message' => 'Category added successfully!', 'category' => $category], 201);
    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find a category by its ID
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        return response()->json($category, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         // Validate the request data
         $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:255|unique:categories,cat_title,' . $id . '|regex:/^[A-Za-z0-9\s]+$/',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Find the category by ID
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
    }
    // Update the category
    $category->cat_title = $request->category;
    $category->save();

    return response()->json(['message' => 'Category updated successfully!', 'category' => $category], 200);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the category by ID
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        // Delete the category
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully!'], 200);
    
    }
}
