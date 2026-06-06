<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KegiatanPdfController;

Route::get('/', function () {
    $setting = \App\Models\Setting::first();
    return view('welcome', compact('setting'));
});

Route::middleware('auth')->get('/kegiatan/{kegiatan}/pdf', [KegiatanPdfController::class, 'generate'])->name('kegiatan.pdf');
