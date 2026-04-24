<?php
// app/Http/Controllers/FeedbackController.php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * POST /api/feedback
     * Kirim feedback dari pengguna
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'   => 'nullable|string|max:255',
            'email'  => 'nullable|email|max:255',
            'pesan'  => 'required|string|min:5',
            'rating' => 'nullable|integer|min:1|max:5',
        ], [
            'pesan.required' => 'Pesan feedback tidak boleh kosong.',
            'pesan.min'      => 'Pesan feedback minimal 5 karakter.',
            'rating.min'     => 'Rating minimal 1.',
            'rating.max'     => 'Rating maksimal 5.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $validated['status'] = 'belum_dibalas';

        $feedback = Feedback::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih atas feedback Anda! Masukan Anda sangat berarti bagi kami.',
            'data'    => $feedback,
        ], 201);
    }

    /**
     * GET /api/feedback
     * Lihat semua feedback (terbaru duluan)
     */
    public function index()
    {
        $feedbacks  = Feedback::latest()->get();
        $rataRating = Feedback::whereNotNull('rating')->avg('rating');

        return response()->json([
            'success'     => true,
            'total'       => $feedbacks->count(),
            'rata_rating' => $rataRating ? round($rataRating, 1) : null,
            'data'        => $feedbacks,
        ]);
    }

    /**
     * DELETE /api/feedback/{id}
     * (Admin) Hapus feedback
     */
    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        return response()->json([
            'success' => true,
            'message' => 'Feedback berhasil dihapus.',
        ]);
    }
}