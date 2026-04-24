<?php

namespace App\Http\Controllers;

use App\Models\StokDarah;
use Illuminate\Http\Request;

class StokDarahController extends Controller
{
    /**
     * GET /api/stok-darah
     * Ambil semua stok darah
     */
    public function index(Request $request)
    {
        $query = StokDarah::query();

        if ($request->filled('nama_rs')) {
            $query->where('nama_rs', 'like', '%' . $request->nama_rs . '%');
        }

        $stok = $query->orderBy('nama_rs')->get();

        // Tambahkan total_stok ke setiap record
        $stok->each(function ($item) {
            $item->total_stok = $item->total_stok;
        });

        return response()->json([
            'success' => true,
            'total'   => $stok->count(),
            'data'    => $stok,
        ]);
    }

    /**
     * GET /api/stok-darah/{id}
     * Detail satu rumah sakit
     */
    public function show($id)
    {
        $stok = StokDarah::findOrFail($id);
        $stok->total_stok = $stok->total_stok;

        return response()->json([
            'success' => true,
            'data'    => $stok,
        ]);
    }

    /**
     * GET /api/stok-darah/ringkasan/total
     * Total stok per golongan darah dari semua RS
     */
    public function totalRingkasan()
    {
        $semua = StokDarah::all();

        $totalPerGolongan = [
            'A+' => $semua->sum('stok_a_plus'),
            'A-' => $semua->sum('stok_a_minus'),
            'B+' => $semua->sum('stok_b_plus'),
            'B-' => $semua->sum('stok_b_minus'),
            'AB+'=> $semua->sum('stok_ab_plus'),
            'AB-'=> $semua->sum('stok_ab_minus'),
            'O+' => $semua->sum('stok_o_plus'),
            'O-' => $semua->sum('stok_o_minus'),
        ];

        $totalKeseluruhan = array_sum($totalPerGolongan);

        return response()->json([
            'success'           => true,
            'total_keseluruhan' => $totalKeseluruhan,
            'per_golongan'      => $totalPerGolongan,
            'per_rumah_sakit'   => $semua->map(function ($rs) {
                return [
                    'id'         => $rs->id,
                    'nama_rs'    => $rs->nama_rs,
                    'total_stok' => $rs->total_stok,
                ];
            }),
        ]);
    }

    /**
     * POST /api/stok-darah
     * Tambah data stok baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_rs'       => 'required|string|max:255',
            'foto'          => 'nullable|string',
            'stok_a_plus'   => 'required|integer|min:0',
            'stok_a_minus'  => 'required|integer|min:0',
            'stok_b_plus'   => 'required|integer|min:0',
            'stok_b_minus'  => 'required|integer|min:0',
            'stok_ab_plus'  => 'required|integer|min:0',
            'stok_ab_minus' => 'required|integer|min:0',
            'stok_o_plus'   => 'required|integer|min:0',
            'stok_o_minus'  => 'required|integer|min:0',
        ]);

        $stok = StokDarah::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data stok darah berhasil ditambahkan.',
            'data'    => $stok,
        ], 201);
    }

    /**
     * PUT /api/stok-darah/{id}
     * Update data stok
     */
    public function update(Request $request, $id)
    {
        $stok = StokDarah::findOrFail($id);

        $validated = $request->validate([
            'nama_rs'       => 'sometimes|string|max:255',
            'foto'          => 'nullable|string',
            'stok_a_plus'   => 'sometimes|integer|min:0',
            'stok_a_minus'  => 'sometimes|integer|min:0',
            'stok_b_plus'   => 'sometimes|integer|min:0',
            'stok_b_minus'  => 'sometimes|integer|min:0',
            'stok_ab_plus'  => 'sometimes|integer|min:0',
            'stok_ab_minus' => 'sometimes|integer|min:0',
            'stok_o_plus'   => 'sometimes|integer|min:0',
            'stok_o_minus'  => 'sometimes|integer|min:0',
        ]);

        $stok->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data stok darah berhasil diperbarui.',
            'data'    => $stok,
        ]);
    }

    /**
     * DELETE /api/stok-darah/{id}
     * Hapus data stok (soft delete)
     */
    public function destroy($id)
    {
        $stok = StokDarah::findOrFail($id);
        $stok->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data stok darah berhasil dihapus.',
        ]);
    }
}