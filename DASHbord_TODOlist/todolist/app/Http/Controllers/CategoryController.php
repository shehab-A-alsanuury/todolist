<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        // Ensure the user is logged in
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You need to be logged in to create a category.');
        }

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7', // Max length for hex color code
        ]);

        // Create the category and associate it with the authenticated user's ID
        Category::create([
            'name' => $request->name,
            'color' => $request->color,
            'user_id' => auth()->id(),  // Add the user_id to associate with the current user
        ]);

        // Redirect back with a success message
        return redirect()->route('todos.index')->with('success', 'Category created successfully.');
    }

    public function update(Request $request, $id)
    {
        // Ensure the user is logged in
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You need to be logged in to update a category.');
        }

        // Find the category by ID
        $category = Category::findOrFail($id);

        // Check if the category belongs to the logged-in user
        if ($category->user_id != auth()->id()) {
            return redirect()->route('todos.index')->with('error', 'You do not have permission to update this category.');
        }

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
        ]);

        // Update the category with the new values
        $category->update([
            'name' => $request->name,
            'color' => $request->color,
        ]);

        // Redirect back with a success message
        return redirect()->route('todos.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        // Ensure the user is logged in
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You need to be logged in to delete a category.');
        }

        // Find and delete the category
        $category = Category::findOrFail($id);

        // Check if the category belongs to the logged-in user
        if ($category->user_id != auth()->id()) {
            return redirect()->route('todos.index')->with('error', 'You do not have permission to delete this category.');
        }

        $category->delete();

        // Redirect back with a success message
        return redirect()->route('todos.index')->with('success', 'Category deleted successfully.');
    }
}
