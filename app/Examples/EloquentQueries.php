<?php

namespace App\Examples;

use App\Models\Book;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Review;

class EloquentQueries
{
    public function librosBaratosDisponibles()
    {
        // Eager Loading: se usa with() para cargar publisher y authors en bloque.
        // Asi se evita el problema N+1 explicado en orm.md y orm.pdf.
        return Book::with(['publisher', 'authors'])
            ->where('price', '<', 1000)
            ->where('stock', '>', 0)
            ->orderBy('price')
            ->get();
    }

    public function buscarLibroPorId()
    {
        $book = Book::find(5);

        if ($book) {
            echo $book->title;
            echo $book->publisher->name;
        }

        return $book;
    }

    public function clientesVip()
    {
        return Customer::where('is_vip', true)
            ->where('email', '!=', '')
            ->orderBy('name')
            ->get();
    }

    public function ordenesEntregadas()
    {
        return Order::where('status', 'delivered')
            ->where('total_amount', '>', 100)
            ->orderBy('ordered_at')
            ->get();
    }

    public function resenasVerificadas()
    {
        return Review::where('rating', '>', 3)
            ->where('is_verified_purchase', true)
            ->orderBy('reviewed_at')
            ->get();
    }

    public function operacionesBasicas()
    {
        $book = Book::create([
            'publisher_id' => 1,
            'title' => 'Laravel ORM Practico',
            'isbn' => '9780000000000',
            'price' => 5000.00,
            'stock' => 10,
            'is_active' => true,
        ]);

        $book = Book::find($book->id);
        $book->price = 4500.00;
        $book->save();
        $book->delete();
    }
}

