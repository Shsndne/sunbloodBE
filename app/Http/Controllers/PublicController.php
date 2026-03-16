<?php

namespace App\Http\Controllers;

use App\Models\StokDarah;
use App\Models\PermintaanDarurat;
use App\Models\Feedback;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    // Landing page
    public function index()
    {
        $totalStok = StokDarah::all()->sum->total_stok;
        return view('public.landing', compact('totalStok'));
    }

    // Halaman stok darah
    public function stokDarah()
    {
        $stoks = StokDarah::all();
        return view('public.stok-darah', compact('stoks'));
    }

    // Form permintaan darurat (GET)
    public function formDarurat()
    {
        return view('public.darurat');
    }

    // Simpan permintaan darurat (POST)
    public function simpanDarurat(Request $request)
    {
        $request->validate([
            'nama_pasien'    => 'required|string|max:255',
            'usia'           => 'required|integer|min:1|max:120',
            'gender'         => 'required|in:laki-laki,perempuan',
            'diagnosis'      => 'nullable|string|max:500',
            'golongan_darah' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'jumlah'         => 'required|integer|min:1',
            'deadline'       => 'nullable|date|after:now',
            'nama_rs'        => 'required|string|max:255',
            'alamat_rs'      => 'nullable|string|max:500',
            'kontak'         => 'required|string|max:20',
            'nama_kontak'    => 'required|string|max:255',
            'catatan'        => 'nullable|string|max:1000',
        ]);

        $data               = $request->all();
        $data['kode']       = PermintaanDarurat::generateKode();
        $data['status']     = 'menunggu';

        $permintaan = PermintaanDarurat::create($data);

        return redirect()->route('darurat.sukses', ['kode' => $permintaan->kode])
            ->with('success', 'Permintaan darah berhasil dikirim!');
    }

    // Halaman sukses setelah kirim permintaan
    public function suksessDarurat($kode)
    {
        $permintaan = PermintaanDarurat::where('kode', $kode)->firstOrFail();
        return view('public.darurat-sukses', compact('permintaan'));
    }

    // Simpan feedback (POST)
    public function simpanFeedback(Request $request)
    {
        $request->validate([
            'feedback_text' => 'required|string|min:10|max:2000',
        ]);

        Feedback::create([
            'feedback_text' => $request->feedback_text,
        ]);

        return back()->with('success', 'Terima kasih atas masukan Anda!');
    }
}