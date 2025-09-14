<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return response()->json(Book::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'publisher' => 'nullable|string',
            'year' => 'nullable|string',
            'stock' => 'required|integer|min:0',
        ]);

        $book = Book::create($data);

        return response()->json($book, 201);
    }

    public function show(Book $book)
    {
        return response()->json($book);
    }


    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'title' => 'sometimes|string',
            'author' => 'sometimes|string',
            'publisher' => 'nullable|string',
            'year' => 'nullable|string',
            'stock' => 'sometimes|integer|min:0',
        ]);

        $book->update($data);

        return response()->json([
            'book' => $book,
            'message' => 'بروزرسانی با موفقیت انجام شد',
        ]);
    }


    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json([
            'message' => 'حذف با موفقیت انجام شد',
        ], 204);
    }
}
