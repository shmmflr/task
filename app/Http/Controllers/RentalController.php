<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RentalController extends Controller
{

    public function store(Request $request)
    {
        $data = $request->validate([
            'book_id' => 'required|exists:books,id',
            'days' => 'required|integer|min:1',
        ]);

        $book = Book::findOrFail($data['book_id']);

        if ($book->stock <= 0) {
            return response()->json(['message' => 'کتاب موجود نیست.'], 400);
        }

        $start = Carbon::today();
        $due = $start->copy()->addDays($data['days']);
        $rental = Rental::create([
            'user_id' => $request->user()->id,
            'book_id' => $book->id,
            'start_date' => $start->toDateString(),
            'due_date' => $due->toDateString(),
            'status' => 'borrowed',
        ]);


        $book->decrement('stock');

        return response()->json($rental, 201);
    }


    public function returnBook(Rental $rental)
    {
        if ($rental->status !== 'borrowed') {
            return response()->json(['message' => 'این کتاب قبلا عودت داده شده.'], 400);
        }

        $rental->return_date = Carbon::today();

        if ($rental->return_date->gt($rental->due_date)) {
            $rental->status = 'late';
        } else {
            $rental->status = 'returned';
        }

        $rental->save();


        $rental->book->increment('stock');

        return response()->json($rental);
    }


    public function myRentals()
    {
        $rentals = Rental::where('user_id', Auth::id())->with('book')->get();
        return response()->json($rentals);
    }
}
