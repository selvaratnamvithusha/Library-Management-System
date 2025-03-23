<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function addCategory(Request $request)
{
    $validated = $request->validate([
        'category' => 'required|string|max:255|unique:categories,cat_title|regex:/^[A-Za-z0-9\s]+$/',
    ], [
        'category.required' => 'The category name is required.',
        'category.string' => 'The category name must be a string.',
        'category.max' => 'The category name must not exceed 255 characters.',
        'category.unique' => 'This category name already exists.',
        'category.regex' => 'The category name can only contain letters, numbers, and spaces.',  
    ]);

    $category = new Category();
    $category->cat_title = $validated['category'];
    $category->save();

    return redirect()->back()->with('message', 'Category added successfully!');
}

public function viewCategories()
{
    $categories = Category::all();
    return view('admin.category', compact('categories'));
}

public function editCategory($id)
{
    $category = Category::findOrFail($id);
    return response()->json(['category' => $category]);
}

public function updateCategory(Request $request, $id)
{
    $validated = $request->validate([
        'cat_name' => 'required|string|max:255|unique:categories,cat_title,' . $id . '|regex:/^[A-Za-z0-9\s]+$/',
    ], [
        'cat_name.required' => 'The category name is required.',
        'cat_name.string' => 'The category name must be a string.',
        'cat_name.max' => 'The category name must not exceed 255 characters.',
        'cat_name.unique' => 'This category name already exists.',
        'cat_name.regex' => 'The category name can only contain letters, numbers, and spaces.', 
    ]);

    $category = Category::findOrFail($id);
    $category->cat_title = $validated['cat_name'];
    $category->save();

    return redirect()->back()->with('message', 'Category updated successfully!');
}

public function destroy($id)
{
    $category = Category::findOrFail($id);
    $category->delete();

    return redirect()->back()->with('message', 'Category deleted successfully!');
}

public function checkCategory(Request $request)
{
    $exists = Category::where('cat_title', $request->category)
                      ->when($request->id, function ($query) use ($request) {
                          return $query->where('id', '!=', $request->id);
                      })
                      ->exists();

    return response()->json(['exists' => $exists]);
}


}
