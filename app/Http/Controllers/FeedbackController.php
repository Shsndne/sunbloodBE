<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    /**
     * Menampilkan halaman feedback (untuk admin)
     */
    public function index()
    {
        return view('admin.feedback'); // FIX: hapus titik di akhir
    }

    /**
     * Menyimpan feedback baru dari user
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'feedback_text' => 'required|string|min:20|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $feedback = Feedback::create([
                'feedback_text' => $request->feedback_text,
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Feedback berhasil dikirim! Terima kasih atas masukannya.',
                'data' => $feedback
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim feedback. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * API untuk mengambil semua feedback (untuk admin)
     */
    public function getFeedback(Request $request)
    {
        $query = Feedback::query();

        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $feedbacks = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $feedbacks
        ]);
    }

    /**
     * API untuk memberikan response admin
     */
    public function respond(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'admin_response' => 'required|string|min:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $feedback = Feedback::findOrFail($id);
            $feedback->respond($request->admin_response);

            return response()->json([
                'success' => true,
                'message' => 'Respons admin berhasil ditambahkan',
                'data' => $feedback
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan respons'
            ], 500);
        }
    }

    /**
     * API untuk mengupdate status feedback
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,read,responded'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $feedback = Feedback::findOrFail($id);

            if ($request->status === 'read') {
                $feedback->markAsRead();
            } else {
                $feedback->status = $request->status;
                $feedback->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui',
                'data' => $feedback
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status'
            ], 500);
        }
    }

    /**
     * API untuk menghapus feedback
     */
    public function destroy($id)
    {
        try {
            $feedback = Feedback::findOrFail($id);
            $feedback->delete();

            return response()->json([
                'success' => true,
                'message' => 'Feedback berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus feedback'
            ], 500);
        }
    }

    /**
     * Mendapatkan statistik feedback
     */
    public function getStats()
    {
        try {
            $stats = [
                'total' => Feedback::count(),
                'pending' => Feedback::pending()->count(),
                'read' => Feedback::read()->count(),
                'responded' => Feedback::responded()->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik'
            ], 500);
        }
    }
}
