<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RumahSakit;
use App\Models\StokDarah;
use App\Models\PermintaanDarah;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Get data for dashboard (API endpoint)
     */
    public function getData()
    {
        try {
            // Total Rumah Sakit
            $totalRS = RumahSakit::count();

            // Total Stok Darah
            $totalStok = StokDarah::sum('stok_a') + 
                        StokDarah::sum('stok_b') + 
                        StokDarah::sum('stok_ab') + 
                        StokDarah::sum('stok_o');

            // Distribusi Stok per Golongan
            $distribusiStok = [
                'A' => StokDarah::sum('stok_a'),
                'B' => StokDarah::sum('stok_b'),
                'AB' => StokDarah::sum('stok_ab'),
                'O' => StokDarah::sum('stok_o'),
            ];

            // Permintaan Darurat Aktif
            $permintaanDaruratAktif = PermintaanDarah::where('status', 'darurat')
                ->where('status_pemenuhan', 'belum')
                ->count();

            // Permintaan Terpenuhi Bulan Ini
            $permintaanTerpenuhi = PermintaanDarah::where('status_pemenuhan', 'terpenuhi')
                ->whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))
                ->count();

            // Tren Permintaan 12 Bulan Terakhir
            $trenPermintaan = $this->getTrenPermintaan();

            return response()->json([
                'success' => true,
                'total_rs' => $totalRS,
                'total_stok' => $totalStok,
                'distribusi_stok' => $distribusiStok,
                'permintaan_darurat_aktif' => $permintaanDaruratAktif,
                'permintaan_terpenuhi_bulan_ini' => $permintaanTerpenuhi,
                'tren_permintaan' => $trenPermintaan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data dashboard',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get trend permintaan per bulan
     */
    private function getTrenPermintaan()
    {
        $bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $tahun = date('Y');
        
        $darurat = [];
        $terpenuhi = [];

        for ($i = 1; $i <= 12; $i++) {
            // Hitung permintaan darurat per bulan
            $jmlDarurat = PermintaanDarah::where('status', 'darurat')
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', $tahun)
                ->count();
            
            // Hitung permintaan terpenuhi per bulan
            $jmlTerpenuhi = PermintaanDarah::where('status_pemenuhan', 'terpenuhi')
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', $tahun)
                ->count();

            $darurat[] = $jmlDarurat;
            $terpenuhi[] = $jmlTerpenuhi;
        }

        return [
            'labels' => $bulan,
            'darurat' => $darurat,
            'terpenuhi' => $terpenuhi
        ];
    }

    /**
     * Get statistik cepat untuk dashboard
     */
    public function getStatistikCepat()
    {
        try {
            // Total semua stok
            $totalStok = StokDarah::sum('stok_a') + 
                        StokDarah::sum('stok_b') + 
                        StokDarah::sum('stok_ab') + 
                        StokDarah::sum('stok_o');

            // Rumah Sakit dengan stok kritis (total < 30)
            $rsKritis = StokDarah::selectRaw('*, (stok_a + stok_b + stok_ab + stok_o) as total_stok')
                ->having('total_stok', '<', 30)
                ->count();

            // Rata-rata stok per RS
            $rataStok = StokDarah::count() > 0 ? round($totalStok / StokDarah::count()) : 0;

            return response()->json([
                'success' => true,
                'total_stok' => $totalStok,
                'rs_kritis' => $rsKritis,
                'rata_rata_stok' => $rataStok,
                'total_rs' => RumahSakit::count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat statistik',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get data untuk chart distribusi
     */
    public function getDistribusiStok()
    {
        try {
            $data = [
                'A' => StokDarah::sum('stok_a'),
                'B' => StokDarah::sum('stok_b'),
                'AB' => StokDarah::sum('stok_ab'),
                'O' => StokDarah::sum('stok_o')
            ];

            $total = array_sum($data);

            // Hitung persentase
            $persentase = [];
            foreach ($data as $gol => $jumlah) {
                $persentase[$gol] = $total > 0 ? round(($jumlah / $total) * 100, 1) : 0;
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'total' => $total,
                'persentase' => $persentase
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data distribusi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}