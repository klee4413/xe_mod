<?php

namespace App\Ai\Agents;

use Laravel\Ai\SDK\Agent;
use Illuminate\Support\Facades\DB;

class ResearchLibrarian extends Agent
{
    public function instructions(): string
    {
        return "You are the Research Librarian for Gemini AI College. Your task is to extract relevant segments from book_db to ground student inquiries in the official curriculum.";
    }

    public function findContext(string $topic)
    {
        // Grounding reasoning in the books connection
        return DB::connection('books')->table('textbooks')
            ->where('content', 'like', "%{$topic}%")
            ->limit(2)
            ->get();
    }
}
