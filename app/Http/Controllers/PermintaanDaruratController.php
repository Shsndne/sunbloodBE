<?php
// app/Http/Controllers/PermintaanDaruratController.php

namespace App\Http\Controllers;

use App\Models\PermintaanDarurat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermintaanDaruratController extends Controller
{
    /**
     * POST /api/permintaan-darurat
     * Kirim permintaan darah darurat & dapatkan nomor resi
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pasien'        => 'required|string|max:255',
            'usia_pasien'        => 'required|integer|min:0|max:150',
            'jenis_kelamin'      => 'required|in:laki-laki,perempuan',
            'diagnosis'          => 'required|string',
            'golongan_darah'     => 'required|in:A,B,AB,O',
            'rhesus'             => 'required|in:+,-',
            'jumlah_kantong'     => 'required|integer|min:1',
            'tanggal_dibutuhkan' => 'required|date|after_or_equal:today',
            'tingkat_urgensi'    => 'required|in:darurat,normal,terjadwal',
            'nama_rumah_sakit'   => 'required|string|max:255',
            'alamat_lengkap'     => 'required|string',
            'nama_kontak'        => 'required|string|max:255',
            'telepon_kontak'     => 'required|string|max:20',
            'pernyataan_setuju'  => 'required|accepted',
        ], [
            'pernyataan_setuju.accepted'         => 'Anda harus menyetujui pernyataan kebenaran data.',
            'tanggal_dibutuhkan.after_or_equal'  => 'Tanggal dibutuhkan tidak boleh sebelum hari ini.',
            'nama_pasien.required'               => 'Nama pasien wajib diisi.',
            'usia_pasien.required'               => 'Usia pasien wajib diisi.',
            'jenis_kelamin.required'             => 'Jenis kelamin wajib dipilih.',
            'diagnosis.required'                 => 'Diagnosis wajib diisi.',
            'golongan_darah.required'            => 'Golongan darah wajib dipilih.',
            'rhesus.required'                    => 'Rhesus wajib dipilih.',
            'jumlah_kantong.required'            => 'Jumlah kantong wajib diisi.',
            'tanggal_dibutuhkan.required'        => 'Tanggal dibutuhkan wajib diisi.',
            'tingkat_urgensi.required'           => 'Tingkat urgensi wajib dipilih.',
            'nama_rumah_sakit.required'          => 'Nama rumah sakit wajib diisi.',
            'alamat_lengkap.required'            => 'Alamat lengkap wajib diisi.',
            'nama_kontak.required'               => 'Nama kontak darurat wajib diisi.',
            'telepon_kontak.required'            => 'Nomor telepon kontak wajib diisi.',
        ]);

        // Generate nomor resi unik
        do {
            $nomorResi = 'SB-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (PermintaanDarurat::where('nomor_resi', $nomorResi)->exists());

        // Isi field wajib
        $validated['nomor_resi']        = $nomorResi;
        $validated['pernyataan_setuju'] = true;
        $validated['status']            = 'menunggu';
        $validated['status_pemenuhan']  = 'belum_terpenuhi';

        // Isi juga kolom lama agar kompatibel
        $validated['kode']          = PermintaanDarurat::generateKode();
        $validated['nama_pasien']   = $validated['nama_pasien'];
        $validated['usia']          = $validated['usia_pasien'];
        $validated['gender']        = ucfirst($validated['jenis_kelamin']);
        $validated['jumlah']        = $validated['jumlah_kantong'];
        $validated['deadline']      = $validated['tanggal_dibutuhkan'] . ' 00:00:00';
        $validated['nama_rs']       = $validated['nama_rumah_sakit'];
        $validated['alamat_rs']     = $validated['alamat_lengkap'];
        $validated['kontak']        = $validated['telepon_kontak'];

        $permintaan = PermintaanDarurat::create($validated);

        return response()->json([
            'success'    => true,
            'message'    => 'Permintaan darah darurat berhasil dikirim. Simpan nomor resi Anda.',
            'nomor_resi' => $permintaan->nomor_resi,
            'data'       => $permintaan,
        ], 201);
    }

    /**
     * GET /api/permintaan-darurat/resi/{nomor_resi}
     * Cek status permintaan berdasarkan nomor resi
     */
    public function cekResi($nomor_resi)
    {
        $data = PermintaanDarurat::where('nomor_resi', $nomor_resi)->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * GET /api/permintaan-darurat
     * (Admin) Lihat semua permintaan darurat
     */
    public function index(Request $request)
    {
        $query = PermintaanDarurat::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tingkat_urgensi')) {
            $query->where('tingkat_urgensi', $request->tingkat_urgensi);
        }

        if ($request->filled('golongan_darah')) {
            $query->where('golongan_darah', $request->golongan_darah);
        }

        // Darurat duluan
        $permintaan = $query
            ->orderByRaw("CASE WHEN tingkat_urgensi = 'darurat' THEN 0 WHEN tingkat_urgensi = 'normal' THEN 1 ELSE 2 END")
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'total'   => $permintaan->count(),
            'data'    => $permintaan,
        ]);
    }

    /**
     * PUT /api/permintaan-darurat/{id}/status
     * (Admin) Update status permintaan
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai,ditolak',
        ]);

        $permintaan = PermintaanDarurat::findOrFail($id);
        $permintaan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Status permintaan berhasil diperbarui.',
            'data'    => $permintaan,
        ]);
    }
}