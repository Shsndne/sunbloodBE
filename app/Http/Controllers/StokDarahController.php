<?php

namespace App\Http\Controllers;

use App\Models\StokDarah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StokDarahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = StokDarah::orderBy('created_at', 'desc')->get();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memuat data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_rs' => 'required|string|max:255',
                'stok_a' => 'required|integer|min:0',
                'stok_b' => 'required|integer|min:0',
                'stok_ab' => 'required|integer|min:0',
                'stok_o' => 'required|integer|min:0',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $data = [
                'nama_rs' => $request->nama_rs,
                'stok_a' => $request->stok_a,
                'stok_b' => $request->stok_b,
                'stok_ab' => $request->stok_ab,
                'stok_o' => $request->stok_o
            ];

            // Upload foto jika ada
            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('rumah-sakit', 'public');
                $data['foto'] = $path;
            }

            $stokDarah = StokDarah::create($data);

            return response()->json([
                'message' => 'Data berhasil disimpan',
                'data' => $stokDarah
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $data = StokDarah::findOrFail($id);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $stokDarah = StokDarah::findOrFail($id);

            $request->validate([
                'nama_rs' => 'required|string|max:255',
                'stok_a' => 'required|integer|min:0',
                'stok_b' => 'required|integer|min:0',
                'stok_ab' => 'required|integer|min:0',
                'stok_o' => 'required|integer|min:0',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $data = [
                'nama_rs' => $request->nama_rs,
                'stok_a' => $request->stok_a,
                'stok_b' => $request->stok_b,
                'stok_ab' => $request->stok_ab,
                'stok_o' => $request->stok_o
            ];

            // Upload foto baru jika ada
            if ($request->hasFile('foto')) {
                // Hapus foto lama
                if ($stokDarah->foto) {
                    Storage::disk('public')->delete($stokDarah->foto);
                }
                
                $path = $request->file('foto')->store('rumah-sakit', 'public');
                $data['foto'] = $path;
            }

            $stokDarah->update($data);

            return response()->json([
                'message' => 'Data berhasil diperbarui',
                'data' => $stokDarah
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $stokDarah = StokDarah::findOrFail($id);

            // Hapus foto jika ada
            if ($stokDarah->foto) {
                Storage::disk('public')->delete($stokDarah->foto);
            }

            $stokDarah->delete();

            return response()->json([
                'message' => 'Data berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get total stok per golongan (untuk dashboard)
     */
    public function getTotalStok()
    {
        try {
            $totalA = StokDarah::sum('stok_a');
            $totalB = StokDarah::sum('stok_b');
            $totalAB = StokDarah::sum('stok_ab');
            $totalO = StokDarah::sum('stok_o');

            return response()->json([
                'A' => $totalA,
                'B' => $totalB,
                'AB' => $totalAB,
                'O' => $totalO,
                'total' => $totalA + $totalB + $totalAB + $totalO
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memuat total stok',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}