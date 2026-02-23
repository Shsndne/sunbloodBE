<?php

namespace App\Http\Controllers;

use App\Models\PermintaanDarurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermintaanDaruratController extends Controller
{
    /**
     * Display a listing of the resource with filters.
     */
    public function index(Request $request)
    {
        try {
            $query = PermintaanDarurat::with('rumahSakit')
                ->orderBy('created_at', 'desc');
            
            // Filter by status (DARURAT/NORMAL/TERENCANA)
            if ($request->has('status') && !empty($request->status)) {
                $query->where('status', $request->status);
            }
            
            // Filter by pemenuhan (belum/diproses/terpenuhi)
            if ($request->has('pemenuhan') && !empty($request->pemenuhan)) {
                $query->where('status_pemenuhan', $request->pemenuhan);
            }
            
            // Filter by golongan darah
            if ($request->has('golongan') && !empty($request->golongan)) {
                $query->where('golongan_darah', $request->golongan);
            }
            
            // Search by nama pasien or rumah sakit
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_pasien', 'LIKE', "%{$search}%")
                      ->orWhere('nama_rs', 'LIKE', "%{$search}%")
                      ->orWhere('kontak', 'LIKE', "%{$search}%");
                });
            }
            
            $perPage = 10;
            $data = $query->paginate($perPage);
            
            return response()->json($data);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memuat data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get summary statistics.
     */
    public function getSummary()
    {
        try {
            $summary = [
                'darurat' => PermintaanDarurat::where('status', 'DARURAT')->count(),
                'belum' => PermintaanDarurat::where('status_pemenuhan', 'belum')->count(),
                'diproses' => PermintaanDarurat::where('status_pemenuhan', 'diproses')->count(),
                'terpenuhi' => PermintaanDarurat::where('status_pemenuhan', 'terpenuhi')->count(),
            ];
            
            return response()->json($summary);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memuat ringkasan',
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
            $validator = Validator::make($request->all(), [
                'nama_pasien' => 'required|string|max:255',
                'usia' => 'required|integer|min:0|max:150',
                'gender' => 'required|in:Laki-laki,Perempuan',
                'diagnosis' => 'nullable|string',
                'golongan_darah' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
                'jumlah' => 'required|integer|min:1|max:50',
                'deadline' => 'required|date',
                'status' => 'required|in:DARURAT,NORMAL,TERENCANA',
                'nama_rs' => 'required|string|max:255',
                'alamat_rs' => 'nullable|string',
                'kontak' => 'required|string|max:20',
                'nama_kontak' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Generate kode unik
            $kode = 'BLD-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

            $data = $request->all();
            $data['kode'] = $kode;
            $data['status_pemenuhan'] = 'belum';

            $permintaan = PermintaanDarurat::create($data);

            return response()->json([
                'message' => 'Permintaan berhasil dibuat',
                'data' => $permintaan
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
            $data = PermintaanDarurat::findOrFail($id);
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
            $permintaan = PermintaanDarurat::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nama_pasien' => 'sometimes|string|max:255',
                'usia' => 'sometimes|integer|min:0|max:150',
                'gender' => 'sometimes|in:Laki-laki,Perempuan',
                'diagnosis' => 'nullable|string',
                'golongan_darah' => 'sometimes|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
                'jumlah' => 'sometimes|integer|min:1|max:50',
                'deadline' => 'sometimes|date',
                'status' => 'sometimes|in:DARURAT,NORMAL,TERENCANA',
                'nama_rs' => 'sometimes|string|max:255',
                'alamat_rs' => 'nullable|string',
                'kontak' => 'sometimes|string|max:20',
                'nama_kontak' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $permintaan->update($request->all());

            return response()->json([
                'message' => 'Data berhasil diperbarui',
                'data' => $permintaan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update status pemenuhan.
     */
    public function proses(Request $request, $id)
    {
        try {
            $permintaan = PermintaanDarurat::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'status_pemenuhan' => 'required|in:diproses,terpenuhi',
                'catatan' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $permintaan->status_pemenuhan = $request->status_pemenuhan;
            $permintaan->catatan = $request->catatan;
            $permintaan->save();

            return response()->json([
                'message' => 'Status berhasil diperbarui',
                'data' => $permintaan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui status',
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
            $permintaan = PermintaanDarurat::findOrFail($id);
            $permintaan->delete();

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
     * Get statistics for dashboard.
     */
    public function getStatistics()
    {
        try {
            $now = now();
            $today = $now->format('Y-m-d');
            
            $statistics = [
                'hari_ini' => PermintaanDarurat::whereDate('created_at', $today)->count(),
                'darurat_aktif' => PermintaanDarurat::where('status', 'DARURAT')
                    ->where('status_pemenuhan', '!=', 'terpenuhi')
                    ->count(),
                'rata_rata_per_hari' => $this->getAveragePerDay(),
                'golongan_terbanyak' => $this->getMostRequestedBloodType(),
            ];
            
            return response()->json($statistics);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memuat statistik',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get average requests per day (last 30 days).
     */
    private function getAveragePerDay()
    {
        $thirtyDaysAgo = now()->subDays(30);
        $total = PermintaanDarurat::where('created_at', '>=', $thirtyDaysAgo)->count();
        
        return $total > 0 ? round($total / 30, 1) : 0;
    }

    /**
     * Get most requested blood type.
     */
    private function getMostRequestedBloodType()
    {
        $data = PermintaanDarurat::select('golongan_darah')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('golongan_darah')
            ->orderBy('total', 'desc')
            ->first();
            
        return $data ? $data->golongan_darah : '-';
    }
}