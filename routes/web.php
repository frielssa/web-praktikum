<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

// Daftar tiket (HTML)
Route::get('/tickets', [TicketController::class, 'index'])
    ->name('tickets.index');

// Detail tiket (HTML) dengan parameter {ticket} dibatasi hanya angka
Route::get('/tickets/{ticket}', [TicketController::class, 'show'])
    ->whereNumber('ticket')
    ->name('tickets.show');

// Detail tiket (JSON) dengan parameter {ticket} dibatasi hanya angka
Route::get('/api/tickets/{ticket}', [TicketController::class, 'showJson'])
    ->whereNumber('ticket')
    ->name('tickets.show-json');

// "{{ route('tickets.show', ['ticket' => 1]) }}"