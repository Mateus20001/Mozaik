<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'pages' => 'nullable|integer',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('coverimages', 'public');
            $validated['cover_image'] = $path;
        }

        $book = Book::create($validated);

        return response()->json($book);
    }

    public function update(Request $request, $id)
    {
        if ($request->isMethod('put') && empty($request->all()) && !empty($_POST)) {
            $request->merge($_POST);
        }

        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'pages' => 'nullable|integer',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('coverimages', 'public');
            $validated['cover_image'] = $path;
        }

        $validated['updated_at'] = now(); // ✅ manually update timestamp

        $book->update($validated);

        return response()->json([
            'message' => 'Book updated successfully',
            'book' => $book
        ]);
    }

    public function delete($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        return response()->json(['message' => 'Book deleted']);
    }

    public function index()
    {
        $books = Book::all();
        return response()->json($books);
    }

    public function searchByTitle($title)
    {
        $books = Book::where('title', 'like', "%{$title}%")->get();
        return response()->json($books);
    }
}
