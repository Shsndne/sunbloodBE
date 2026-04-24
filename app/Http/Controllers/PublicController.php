<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StokDarah;

class PublicController extends Controller
{
    public function landing()
    {
        $stoks = StokDarah::orderBy('nama_rs')->get();
        $totalKantong = $stoks->sum(fn($s) => $s->total_stok);
        return view('public.landing', compact('stoks', 'totalKantong'));
    }

    public function konsultasi()
    {
        return view('public.konsultasi');
    }

    public function stokDarah()
    {
        $stoks = StokDarah::orderBy('nama_rs')->get();
        $stokPerGolongan = [
            'A+'  => $stoks->sum('stok_a_plus'),
            'A-'  => $stoks->sum('stok_a_minus'),
            'B+'  => $stoks->sum('stok_b_plus'),
            'B-'  => $stoks->sum('stok_b_minus'),
            'AB+' => $stoks->sum('stok_ab_plus'),
            'AB-' => $stoks->sum('stok_ab_minus'),
            'O+'  => $stoks->sum('stok_o_plus'),
            'O-'  => $stoks->sum('stok_o_minus'),
        ];
        $totalKantong = array_sum($stokPerGolongan);
        return view('public.stok-darah', compact('stoks', 'stokPerGolongan', 'totalKantong'));
    }

    public function darurat()
    {
        return view('public.darurat');
    }

    public function feedback()
    {
        return view('public.feedback');
    }
}