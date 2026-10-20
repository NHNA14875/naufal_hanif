<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Auth::user()->notifications()->latest()->get();

        return view('notifikasi.index', compact('notifikasi'));
    }

    public function baca($id)
    {
        // Hanya notifikasi milik user yang login
        Auth::user()->notifications()->findOrFail($id)->markAsRead();

        return back();
    }
}
