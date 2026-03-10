<?php

namespace App\Http\Controllers;

use App\Models\StokDarah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class StokDarahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = StokDarah::orderBy('created_at', 'desc')->get();
            
            // Transform data untuk tampilan
            $transformedData = $data->map(function ($item) {
                // Hitung total per golongan jika attribute tidak tersedia
                $totalA = ($item->stok_a_plus ?? 0) + ($item->stok_a_minus ?? 0);
                $totalB = ($item->stok_b_plus ?? 0) + ($item->stok_b_minus ?? 0);
                $totalAB = ($item->stok_ab_plus ?? 0) + ($item->stok_ab_minus ?? 0);
                $totalO = ($item->stok_o_plus ?? 0) + ($item->stok_o_minus ?? 0);
                $totalAll = $totalA + $totalB + $totalAB + $totalO;
                
                // Tentukan status berdasarkan total
                $status = 'Aman';
                if ($totalAll < 30) {
                    $status = 'Kritis';
                } elseif ($totalAll < 70) {
                    $status = 'Sedang';
                }
                
                return [
                    'id' => $item->id,
                    'nama_rs' => $item->nama_rs,
                    'foto' => $item->foto ? asset('storage/' . $item->foto) : null,
                    // KIRIM DETAIL RHESUS (pastikan nilainya selalu ada)
                    'stok_a_plus' => (int) ($item->stok_a_plus ?? 0),
                    'stok_a_minus' => (int) ($item->stok_a_minus ?? 0),
                    'stok_b_plus' => (int) ($item->stok_b_plus ?? 0),
                    'stok_b_minus' => (int) ($item->stok_b_minus ?? 0),
                    'stok_ab_plus' => (int) ($item->stok_ab_plus ?? 0),
                    'stok_ab_minus' => (int) ($item->stok_ab_minus ?? 0),
                    'stok_o_plus' => (int) ($item->stok_o_plus ?? 0),
                    'stok_o_minus' => (int) ($item->stok_o_minus ?? 0),
                    // TOTALS (untuk referensi)
                    'total_a' => $totalA,
                    'total_b' => $totalB,
                    'total_ab' => $totalAB,
                    'total_o' => $totalO,
                    'total' => $totalAll,
                    'status' => $status,
                    'created_at' => $item->created_at ? $item->created_at->format('d/m/Y H:i') : null,
                    'updated_at' => $item->updated_at ? $item->updated_at->format('d/m/Y H:i') : null
                ];
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dimuat',
                'data' => $transformedData
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error index: ' . $e->getMessage());
            return response()->json([
                'success' => false,
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
            // Validasi input
            $request->validate([
                'nama_rs' => 'required|string|max:255',
                'stok_a' => 'required|integer|min:0',
                'stok_b' => 'required|integer|min:0',
                'stok_ab' => 'required|integer|min:0',
                'stok_o' => 'required|integer|min:0',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            // Konversi total ke detail + dan - (pembagian 50:50)
            $stokA = (int) $request->stok_a;
            $stokB = (int) $request->stok_b;
            $stokAB = (int) $request->stok_ab;
            $stokO = (int) $request->stok_o;
            
            $data = [
                'nama_rs' => $request->nama_rs,
                'stok_a_plus' => (int) floor($stokA / 2),
                'stok_a_minus' => (int) ceil($stokA / 2),
                'stok_b_plus' => (int) floor($stokB / 2),
                'stok_b_minus' => (int) ceil($stokB / 2),
                'stok_ab_plus' => (int) floor($stokAB / 2),
                'stok_ab_minus' => (int) ceil($stokAB / 2),
                'stok_o_plus' => (int) floor($stokO / 2),
                'stok_o_minus' => (int) ceil($stokO / 2),
            ];

            // Upload foto jika ada
            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('rumah-sakit', 'public');
                $data['foto'] = $path;
            }

            $stokDarah = StokDarah::create($data);

            // Hitung total untuk response
            $totalA = $stokDarah->stok_a_plus + $stokDarah->stok_a_minus;
            $totalB = $stokDarah->stok_b_plus + $stokDarah->stok_b_minus;
            $totalAB = $stokDarah->stok_ab_plus + $stokDarah->stok_ab_minus;
            $totalO = $stokDarah->stok_o_plus + $stokDarah->stok_o_minus;
            $totalAll = $totalA + $totalB + $totalAB + $totalO;

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'data' => [
                    'id' => $stokDarah->id,
                    'nama_rs' => $stokDarah->nama_rs,
                    'foto' => $stokDarah->foto ? asset('storage/' . $stokDarah->foto) : null,
                    'stok_a_plus' => $stokDarah->stok_a_plus,
                    'stok_a_minus' => $stokDarah->stok_a_minus,
                    'stok_b_plus' => $stokDarah->stok_b_plus,
                    'stok_b_minus' => $stokDarah->stok_b_minus,
                    'stok_ab_plus' => $stokDarah->stok_ab_plus,
                    'stok_ab_minus' => $stokDarah->stok_ab_minus,
                    'stok_o_plus' => $stokDarah->stok_o_plus,
                    'stok_o_minus' => $stokDarah->stok_o_minus,
                    'total_a' => $totalA,
                    'total_b' => $totalB,
                    'total_ab' => $totalAB,
                    'total_o' => $totalO,
                    'total' => $totalAll
                ]
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error store: ' . $e->getMessage());
            return response()->json([
                'success' => false,
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
            $stokDarah = StokDarah::findOrFail($id);
            
            // Hitung total
            $totalA = $stokDarah->stok_a_plus + $stokDarah->stok_a_minus;
            $totalB = $stokDarah->stok_b_plus + $stokDarah->stok_b_minus;
            $totalAB = $stokDarah->stok_ab_plus + $stokDarah->stok_ab_minus;
            $totalO = $stokDarah->stok_o_plus + $stokDarah->stok_o_minus;
            $totalAll = $totalA + $totalB + $totalAB + $totalO;
            
            // Tentukan status
            $status = 'Aman';
            if ($totalAll < 30) {
                $status = 'Kritis';
            } elseif ($totalAll < 70) {
                $status = 'Sedang';
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $stokDarah->id,
                    'nama_rs' => $stokDarah->nama_rs,
                    'foto' => $stokDarah->foto ? asset('storage/' . $stokDarah->foto) : null,
                    // Detail rhesus
                    'stok_a_plus' => $stokDarah->stok_a_plus,
                    'stok_a_minus' => $stokDarah->stok_a_minus,
                    'stok_b_plus' => $stokDarah->stok_b_plus,
                    'stok_b_minus' => $stokDarah->stok_b_minus,
                    'stok_ab_plus' => $stokDarah->stok_ab_plus,
                    'stok_ab_minus' => $stokDarah->stok_ab_minus,
                    'stok_o_plus' => $stokDarah->stok_o_plus,
                    'stok_o_minus' => $stokDarah->stok_o_minus,
                    // Total per golongan
                    'stok_a' => $totalA,
                    'stok_b' => $totalB,
                    'stok_ab' => $totalAB,
                    'stok_o' => $totalO,
                    'total' => $totalAll,
                    'status' => $status,
                    'created_at' => $stokDarah->created_at,
                    'updated_at' => $stokDarah->updated_at
                ]
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error show: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $stokDarah = StokDarah::findOrFail($id);

            // Validasi input
            $request->validate([
                'nama_rs' => 'required|string|max:255',
                'stok_a' => 'required|integer|min:0',
                'stok_b' => 'required|integer|min:0',
                'stok_ab' => 'required|integer|min:0',
                'stok_o' => 'required|integer|min:0',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            // Konversi total ke detail
            $stokA = (int) $request->stok_a;
            $stokB = (int) $request->stok_b;
            $stokAB = (int) $request->stok_ab;
            $stokO = (int) $request->stok_o;
            
            $data = [
                'nama_rs' => $request->nama_rs,
                'stok_a_plus' => (int) floor($stokA / 2),
                'stok_a_minus' => (int) ceil($stokA / 2),
                'stok_b_plus' => (int) floor($stokB / 2),
                'stok_b_minus' => (int) ceil($stokB / 2),
                'stok_ab_plus' => (int) floor($stokAB / 2),
                'stok_ab_minus' => (int) ceil($stokAB / 2),
                'stok_o_plus' => (int) floor($stokO / 2),
                'stok_o_minus' => (int) ceil($stokO / 2),
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

            // Hitung total untuk response
            $totalA = $stokDarah->stok_a_plus + $stokDarah->stok_a_minus;
            $totalB = $stokDarah->stok_b_plus + $stokDarah->stok_b_minus;
            $totalAB = $stokDarah->stok_ab_plus + $stokDarah->stok_ab_minus;
            $totalO = $stokDarah->stok_o_plus + $stokDarah->stok_o_minus;
            $totalAll = $totalA + $totalB + $totalAB + $totalO;

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => [
                    'id' => $stokDarah->id,
                    'nama_rs' => $stokDarah->nama_rs,
                    'foto' => $stokDarah->foto ? asset('storage/' . $stokDarah->foto) : null,
                    'stok_a_plus' => $stokDarah->stok_a_plus,
                    'stok_a_minus' => $stokDarah->stok_a_minus,
                    'stok_b_plus' => $stokDarah->stok_b_plus,
                    'stok_b_minus' => $stokDarah->stok_b_minus,
                    'stok_ab_plus' => $stokDarah->stok_ab_plus,
                    'stok_ab_minus' => $stokDarah->stok_ab_minus,
                    'stok_o_plus' => $stokDarah->stok_o_plus,
                    'stok_o_minus' => $stokDarah->stok_o_minus,
                    'total_a' => $totalA,
                    'total_b' => $totalB,
                    'total_ab' => $totalAB,
                    'total_o' => $totalO,
                    'total' => $totalAll
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error update: ' . $e->getMessage());
            return response()->json([
                'success' => false,
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
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error destroy: ' . $e->getMessage());
            return response()->json([
                'success' => false,
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
            $totalAPlus = (int) StokDarah::sum('stok_a_plus');
            $totalAMinus = (int) StokDarah::sum('stok_a_minus');
            $totalBPlus = (int) StokDarah::sum('stok_b_plus');
            $totalBMinus = (int) StokDarah::sum('stok_b_minus');
            $totalABPlus = (int) StokDarah::sum('stok_ab_plus');
            $totalABMinus = (int) StokDarah::sum('stok_ab_minus');
            $totalOPlus = (int) StokDarah::sum('stok_o_plus');
            $totalOMinus = (int) StokDarah::sum('stok_o_minus');

            $totalA = $totalAPlus + $totalAMinus;
            $totalB = $totalBPlus + $totalBMinus;
            $totalAB = $totalABPlus + $totalABMinus;
            $totalO = $totalOPlus + $totalOMinus;
            $totalAll = $totalA + $totalB + $totalAB + $totalO;

            return response()->json([
                'success' => true,
                'data' => [
                    // Detail per rhesus
                    'A+' => $totalAPlus,
                    'A-' => $totalAMinus,
                    'B+' => $totalBPlus,
                    'B-' => $totalBMinus,
                    'AB+' => $totalABPlus,
                    'AB-' => $totalABMinus,
                    'O+' => $totalOPlus,
                    'O-' => $totalOMinus,
                    // Total per golongan
                    'total_a' => $totalA,
                    'total_b' => $totalB,
                    'total_ab' => $totalAB,
                    'total_o' => $totalO,
                    'total' => $totalAll
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getTotalStok: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat total stok',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistik stok (untuk chart)
     */
    public function getStatistik()
    {
        try {
            // Hitung total per golongan
            $totalA = StokDarah::sum('stok_a_plus') + StokDarah::sum('stok_a_minus');
            $totalB = StokDarah::sum('stok_b_plus') + StokDarah::sum('stok_b_minus');
            $totalAB = StokDarah::sum('stok_ab_plus') + StokDarah::sum('stok_ab_minus');
            $totalO = StokDarah::sum('stok_o_plus') + StokDarah::sum('stok_o_minus');
            
            // Hitung status stok
            $allData = StokDarah::all();
            $kritis = 0;
            $sedang = 0;
            $aman = 0;
            
            foreach ($allData as $item) {
                $total = $item->stok_a_plus + $item->stok_a_minus + 
                         $item->stok_b_plus + $item->stok_b_minus + 
                         $item->stok_ab_plus + $item->stok_ab_minus + 
                         $item->stok_o_plus + $item->stok_o_minus;
                
                if ($total < 30) {
                    $kritis++;
                } elseif ($total < 70) {
                    $sedang++;
                } else {
                    $aman++;
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'stok' => [
                        'A' => (int) $totalA,
                        'B' => (int) $totalB,
                        'AB' => (int) $totalAB,
                        'O' => (int) $totalO,
                    ],
                    'status' => [
                        'kritis' => $kritis,
                        'sedang' => $sedang,
                        'aman' => $aman
                    ],
                    'total_rs' => $allData->count()
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getStatistik: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat statistik',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}