<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookController extends Controller
{
    public function create(Request $request)
    {
        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'pages' => $request->pages,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'cover_image' => $request->cover_image
        ]);

        return response()->json($book);
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $book->update($request->all());
        return response()->json($book);
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
