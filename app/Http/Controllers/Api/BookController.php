<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BookResource::collection(Book::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $validated = $request->validate([
                'title' => 'required|string|min:3|max:255',
                'author' => 'required|string|min:3|max:100',
                'summary' => 'required|string|min:10|max:500',
                'isbn' => 'required|string|size:13|unique:books,isbn',
            ]);

            $book = Book::create($validated);

            return new BookResource($book);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return new BookResource($book);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'author' => 'required|string|min:3|max:100',
            'summary' => 'required|string|min:10|max:500',
            'isbn' => 'required|string|size:13|unique:books,isbn,' . $book->id, // nom de la table, nom colonne, exception (id de l'objet, car on ne veut pas le réécrire)
        ]);

        $book->update($validated);

        return new BookResource($book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $bookData = new BookResource($book);
        $book->delete();

        return response()->json([
            'message' => 'Book deleted successfully',
            'book' => $bookData
        ], 200);
    }
}
