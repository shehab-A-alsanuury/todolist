<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\Category;  // Add this import
use Illuminate\Http\Request;

class TodoController extends Controller
{
    // Remove the first index() method since it's duplicated
    public function index()
    {
        $categories = Category::with('todos')->get();
        return view('index', compact('categories'));
    }
    public function edit($id)
    {
        $todo = Todo::findOrFail($id);
        $categories = Category::all();  // Fetch categories for the dropdown
    
        return view('todos.edit', compact('todo', 'categories'));
    }
    public function dashboard()
    {
        $todos = Todo::all();
        return view('dashboard', compact('todos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        Todo::create([
            'title' => $request->title,
            'description' => $request->description ?? '',
            'is_completed' => false,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('todos.index')->with('success', 'To-Do item created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',  // Add category validation
        ]);

        $todo = Todo::findOrFail($id);
        $todo->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,  // Add category update
        ]);

        return redirect()->route('todos.index')->with('success', 'To-Do item updated successfully.');
    }

    public function toggleComplete($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->update(['is_completed' => !$todo->is_completed]);

        return redirect()->route('todos.index')->with('success', 'To-Do item status updated.');
    }

    public function destroy($id)
    {
        Todo::destroy($id);
        return redirect()->route('todos.index')->with('success', 'To-Do item deleted successfully.');
    }
}