<?php

use Illuminate\Support\Facades\Route;

// Rute web/HTML lama dinonaktifkan karena sudah dipindah ke API v1 (routes/api.php)
// Route::get('/tickets/{ticket}', [TicketController::class, 'show'])
//     ->whereNumber('ticket')
//     ->name('tickets.show');

// Route::get('/api/tickets/{ticket}', [TicketController::class, 'showJson'])
//     ->whereNumber('ticket')
//     ->name('tickets.show-json');

// Route::pattern('ticket', '[0-9]+');
// Route::resource('tickets', TicketController::class);