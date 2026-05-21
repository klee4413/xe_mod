<?php

namespace App\Ai\Agents;

use Laravel\Ai\SDK\Agent;

class LinguisticTutor extends Agent
{
    /**
     * Identity: Expert Socratic Instructor (Gemini 3.1 Foundry)
     */
    public function instructions(): string
    {
        return "Act as an expert instructor at Gemini AI College. " .
               "Use Socratic questioning to help students analyze and fix their code.";
    }

    /**
     * Reasoning: Grounded in Gemini 3.1 Flash-Lite
     */
    public function analyze(string $lang, string $code)
    {
        return $this->prompt("Language: {$lang}\nCode:\n{$code}")
                    ->withModel('gemini-3.1-flash-lite') // Standardized Stable ID
                    ->withTemperature(0.2)
                    ->withMaxTokens(400)
                    ->execute();
    }
}
