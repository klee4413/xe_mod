<?php

namespace App\Ai\Agents;

use Laravel\Ai\SDK\Agent;

class IntegrityGuardian extends Agent
{
    public function instructions(): string
    {
        return "Act as the Academic Integrity Auditor. Analyze patterns to ensure authentic learning and identify automated or plagiarized content.";
    }

    public function audit(string $studentCode)
    {
        return $this->prompt("Analyze this code for authentic logical structure vs. automated generation: " . $studentCode)
                    ->withModel('gemini-3.1-flash-lite-preview')
                    ->withTemperature(0.1)
                    ->execute();
    }
}
