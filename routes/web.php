<?php

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();
Route::middleware('auth')->group(function() {
    Route::get('/home', 'HomeController@index')->name('home');

    Route::resource('pengguna', 'UserController')->middleware(['role.admin','role.waiter','role.kasir']);
    Route::resource('masakan', 'MasakanController')->middleware(['role.admin']);

    Route::middleware(['role.kasir', 'role.waiter','role.admin', 'role.owner'])->group(function() {
        Route::prefix('laporan')->group(function() {
            Route::get('/', 'LaporanController@index')->name('laporan.index');
        });
    });

    Route::middleware(['role.kasir','role.admin'])->group(function() {
        Route::prefix('transaksi')->group(function() {
            Route::get('/', 'TransactionController@index')->name('transaksi.index');
            Route::get('/{id}', 'TransactionController@show')->name('transaksi.show');
            Route::post('/finish/{id}', 'TransactionController@finish')->name('transaksi.finish');
        });
    });

    Route::middleware(['role.pelanggan', 'role.waiter','role.admin'])->group(function() {
        Route::prefix('order')->group(function() {
            Route::get('/', 'OrderController@index')->name('order.index');
            Route::get('/new', 'OrderController@new')->name('order.new');
        });
    });
});