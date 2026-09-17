<?php

use App\Http\Controller\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/ticket', [TicketController::class, 'index'])
    ->name('tickets.index');

Route::get('/tickets/{ticket}', [TicketController::class, 'show'])
    ->whereNumber('ticket')
    ->name('tickets.show');



Route::get('/api/tickets/{ticket}', [TicketController::class, 'showJson'])
    ->whereNumber('ticket')
    ->name('tickets.show-json');

// "{{ route('tickets.show', ['ticket' => 1]) }}"