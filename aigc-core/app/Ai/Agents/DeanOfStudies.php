<?php

namespace App\Ai\Agents;

use Laravel\Ai\SDK\Agent;
use Illuminate\Support\Facades\DB;

class DeanOfStudies extends Agent
{
    public function instructions(): string
    {
        return "You are the Dean of Studies. You analyze historical performance in 'ai-hi-work' to suggest personalized learning paths.";
    }

    public function getTrajectory(int $studentId)
    {
        $history = DB::connection('ai_hi')->table('submissions')
            ->where('student_id', $studentId)
            ->get();

        return $this->prompt("Analyze this scholar's history and predict the next logical challenge: " . json_encode($history))
                    ->withModel('gemini-3.1-flash-lite-preview')
                    ->execute();
    }
}
