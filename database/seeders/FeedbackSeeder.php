<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feedback;
use Carbon\Carbon;

class FeedbackSeeder extends Seeder
{
    public function run()
    {
        $feedbacks = [
            [
                'feedback_text' => 'Pelayanan sangat baik dan cepat. Saya sangat puas dengan hasilnya!',
                'status' => 'read',
                'created_at' => Carbon::now()->subDays(5)
            ],
            [
                'feedback_text' => 'Mohon untuk ditingkatkan lagi sistem pembayarannya, sering error.',
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(3)
            ],
            [
                'feedback_text' => 'Tampilan website menarik dan mudah digunakan. Keep it up!',
                'status' => 'responded',
                'admin_response' => 'Terima kasih atas apresiasinya! Kami akan terus meningkatkan kualitas layanan.',
                'responded_at' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now()->subDays(4)
            ],
            [
                'feedback_text' => 'CS kurang ramah dalam melayani, tolong diperhatikan.',
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(1)
            ],
            [
                'feedback_text' => 'Fitur-fiturnya lengkap, tapi loadingnya agak lambat.',
                'status' => 'read',
                'created_at' => Carbon::now()->subDays(2)
            ]
        ];

        foreach ($feedbacks as $feedback) {
            Feedback::create($feedback);
        }
    }
}