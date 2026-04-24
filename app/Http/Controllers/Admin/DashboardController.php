<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StokDarah;
use App\Models\PermintaanDarurat;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    // ─── DASHBOARD ──────────────────────────────────────────────────────────
    public function index()
    {
        $totalStok       = StokDarah::count();
        $totalPermintaan = PermintaanDarurat::count();
        $permintaanBaru  = PermintaanDarurat::whereIn('status', ['menunggu', 'pending'])->count();
        $totalFeedback   = Feedback::count();
        $feedbackBelum   = Feedback::where('status', 'belum_dibalas')->count();
        $totalUsers      = User::where('role', 'user')->count();

        // 5 permintaan terbaru
        $permintaanTerbaru = PermintaanDarurat::latest()->take(5)->get();

        // 5 feedback terbaru
        $feedbackTerbaru = Feedback::latest()->take(5)->get();

        // Statistik stok per golongan darah (total dari semua RS)
        $semuaStok = StokDarah::all();
        $stokPerGolongan = [
            'A+'  => $semuaStok->sum('stok_a_plus'),
            'A-'  => $semuaStok->sum('stok_a_minus'),
            'B+'  => $semuaStok->sum('stok_b_plus'),
            'B-'  => $semuaStok->sum('stok_b_minus'),
            'AB+' => $semuaStok->sum('stok_ab_plus'),
            'AB-' => $semuaStok->sum('stok_ab_minus'),
            'O+'  => $semuaStok->sum('stok_o_plus'),
            'O-'  => $semuaStok->sum('stok_o_minus'),
        ];
        $totalStokKantong = array_sum($stokPerGolongan);

        return view('admin.dashboard', compact(
            'totalStok',
            'totalPermintaan',
            'permintaanBaru',
            'totalFeedback',
            'feedbackBelum',
            'totalUsers',
            'permintaanTerbaru',
            'feedbackTerbaru',
            'stokPerGolongan',
            'totalStokKantong'
        ));
    }

    // ─── STOK DARAH ─────────────────────────────────────────────────────────
    public function stokIndex()
    {
        $stoks = StokDarah::latest()->paginate(10);
        return view('admin.stok', compact('stoks'));
    }

    public function stokCreate()
    {
        return view('admin.stok-form');
    }

    public function stokStore(Request $request)
    {
        $data = $request->validate([
            'nama_rs'       => 'required|string|max:255',
            'foto'          => 'nullable|image|max:2048',
            'stok_a_plus'   => 'required|integer|min:0',
            'stok_a_minus'  => 'required|integer|min:0',
            'stok_b_plus'   => 'required|integer|min:0',
            'stok_b_minus'  => 'required|integer|min:0',
            'stok_ab_plus'  => 'required|integer|min:0',
            'stok_ab_minus' => 'required|integer|min:0',
            'stok_o_plus'   => 'required|integer|min:0',
            'stok_o_minus'  => 'required|integer|min:0',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('stok-darah', 'public');
        }

        StokDarah::create($data);
        return redirect()->route('admin.stok.index')->with('success', 'Data stok darah berhasil ditambahkan!');
    }

    public function stokEdit(StokDarah $stok)
    {
        return view('admin.stok-form', compact('stok'));
    }

    public function stokUpdate(Request $request, StokDarah $stok)
    {
        $data = $request->validate([
            'nama_rs'       => 'required|string|max:255',
            'foto'          => 'nullable|image|max:2048',
            'stok_a_plus'   => 'required|integer|min:0',
            'stok_a_minus'  => 'required|integer|min:0',
            'stok_b_plus'   => 'required|integer|min:0',
            'stok_b_minus'  => 'required|integer|min:0',
            'stok_ab_plus'  => 'required|integer|min:0',
            'stok_ab_minus' => 'required|integer|min:0',
            'stok_o_plus'   => 'required|integer|min:0',
            'stok_o_minus'  => 'required|integer|min:0',
        ]);

        if ($request->hasFile('foto')) {
            if ($stok->foto) {
                Storage::disk('public')->delete($stok->foto);
            }
            $data['foto'] = $request->file('foto')->store('stok-darah', 'public');
        }

        $stok->update($data);
        return redirect()->route('admin.stok.index')->with('success', 'Data stok darah berhasil diperbarui!');
    }

    public function stokDestroy(StokDarah $stok)
    {
        if ($stok->foto) {
            Storage::disk('public')->delete($stok->foto);
        }
        $stok->delete();
        return back()->with('success', 'Data berhasil dihapus!');
    }

    // ─── PERMINTAAN DARURAT ──────────────────────────────────────────────────
    public function permintaanIndex(Request $request)
    {
        $query = PermintaanDarurat::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('urgensi')) {
            $query->where('tingkat_urgensi', $request->urgensi);
        }

        $permintaans = $query->paginate(15);
        return view('admin.darurat', compact('permintaans'));
    }

    public function permintaanShow(PermintaanDarurat $permintaan)
    {
        return view('admin.darurat-detail', compact('permintaan'));
    }

    public function permintaanUpdateStatus(Request $request, PermintaanDarurat $permintaan)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai,ditolak',
        ]);

        $permintaan->update(['status' => $request->status]);
        return back()->with('success', 'Status permintaan berhasil diperbarui!');
    }

    public function permintaanDestroy(PermintaanDarurat $permintaan)
    {
        $permintaan->delete();
        return back()->with('success', 'Permintaan berhasil dihapus!');
    }

    // ─── FEEDBACK ────────────────────────────────────────────────────────────
    public function feedbackIndex()
    {
        $feedbacks  = Feedback::latest()->paginate(15);
        $rataRating = Feedback::whereNotNull('rating')->avg('rating');
        return view('admin.feedback', compact('feedbacks', 'rataRating'));
    }

    public function feedbackBalas(Request $request, Feedback $feedback)
    {
        $request->validate([
            'admin_response' => 'required|string|min:5|max:2000',
        ]);

        $feedback->update([
            'admin_response' => $request->admin_response,
            'status'         => 'sudah_dibalas',
            'responded_at'   => now(),
        ]);

        return back()->with('success', 'Feedback berhasil dibalas!');
    }

    public function feedbackDestroy(Feedback $feedback)
    {
        $feedback->delete();
        return back()->with('success', 'Feedback berhasil dihapus!');
    }

    // ─── USERS ───────────────────────────────────────────────────────────────
    public function usersIndex()
    {
        $users = User::where('role', 'user')->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function usersDestroy(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Tidak bisa menghapus akun admin!');
        }
        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus!');
    }
}