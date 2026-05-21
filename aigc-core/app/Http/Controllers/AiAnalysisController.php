<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;

class AiAnalysisController extends Controller
{
    public function analyzeCode(Request $request)
    {
        try {
            $validated = $request->validate([
                'lang' => 'required|string',
                'code' => 'required|string',
            ]);

            // Using the bleeding-edge Gemini 3.1 Flash-Lite Stable ID
            $result = Gemini::generativeModel(model: 'gemini-3.1-flash-lite')
                ->withTemperature(0.2) // Precision Tuning
                ->generateContent("Act as a Professor at Gemini AI College. Analyze this {$validated['lang']} code and suggest a 'Foundry' improvement: \n\n" . $validated['code']);

            return response()->json([
                'analysis' => $result->text()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'analysis' => '<div class="text-red-500 font-black italic uppercase">Neural Link Failure: ' . $e->getMessage() . '</div>'
            ], 500);
        }
    }
}
