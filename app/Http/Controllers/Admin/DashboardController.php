<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StokDarah;
use App\Models\PermintaanDarurat;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    // ─── DASHBOARD ──────────────────────────────────────────────
    public function index()
    {
        $totalStok       = StokDarah::count();
        $totalPermintaan = PermintaanDarurat::count();
        $permintaanBaru  = PermintaanDarurat::where('status', 'menunggu')->count();
        $totalFeedback   = Feedback::count();
        $feedbackBelum   = Feedback::where('status', 'belum_dibalas')->count();

        $permintaanTerbaru = PermintaanDarurat::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalStok', 'totalPermintaan', 'permintaanBaru',
            'totalFeedback', 'feedbackBelum', 'permintaanTerbaru'
        ));
    }

    // ─── STOK DARAH ─────────────────────────────────────────────
    public function stokIndex()
    {
        $stoks = StokDarah::withTrashed()->latest()->paginate(10);
        return view('admin.stok.index', compact('stoks'));
    }

    public function stokCreate()
    {
        return view('admin.stok.form');
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
        return view('admin.stok.form', compact('stok'));
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
            if ($stok->foto) Storage::disk('public')->delete($stok->foto);
            $data['foto'] = $request->file('foto')->store('stok-darah', 'public');
        }

        $stok->update($data);
        return redirect()->route('admin.stok.index')->with('success', 'Data stok darah berhasil diperbarui!');
    }

    public function stokDestroy(StokDarah $stok)
    {
        $stok->delete(); // soft delete
        return back()->with('success', 'Data berhasil dihapus!');
    }

    // ─── PERMINTAAN DARURAT ──────────────────────────────────────
    public function permintaanIndex(Request $request)
    {
        $query = PermintaanDarurat::latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $permintaans = $query->paginate(15);
        return view('admin.permintaan.index', compact('permintaans'));
    }

    public function permintaanShow(PermintaanDarurat $permintaan)
    {
        return view('admin.permintaan.show', compact('permintaan'));
    }

    public function permintaanUpdateStatus(Request $request, PermintaanDarurat $permintaan)
    {
        $request->validate([
            'status'           => 'required|in:menunggu,diproses,selesai,ditolak',
            'status_pemenuhan' => 'required|in:belum_terpenuhi,sebagian,terpenuhi',
        ]);

        $permintaan->update($request->only('status', 'status_pemenuhan'));
        return back()->with('success', 'Status permintaan berhasil diperbarui!');
    }

    public function permintaanDestroy(PermintaanDarurat $permintaan)
    {
        $permintaan->delete();
        return back()->with('success', 'Permintaan berhasil dihapus!');
    }

    // ─── FEEDBACK ────────────────────────────────────────────────
    public function feedbackIndex()
    {
        $feedbacks = Feedback::latest()->paginate(15);
        return view('admin.feedback.index', compact('feedbacks'));
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
}