<?php
/**
 * GAC FOUNDRY: Sovereign Bridge v1.1
 * Purpose: Securely exposes Laravel AI Agents to legacy satellite apps.
 * Bypasses: Routing Cache, CSRF Middleware, and Framework Overhead.
 */

// 1. BOOTSTRAP THE FOUNDRY CORE
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Ai\Agents\LinguisticTutor;

// 2. PROTOCOL: CAPTURE INCOMING NEURAL DATA
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['code'])) {
    try {
        // 3. EXECUTE AGENTIC INFERENCE
        $analysis = LinguisticTutor::make()->analyze(
            $input['lang'] ?? 'plain text', 
            $input['code']
        );
        
        // 4. RETURN PROTECTED JSON PAYLOAD
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'analysis' => $analysis
        ]);
    } catch (\Exception $e) {
        header('Content-Type: application/json', true, 500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Foundry Execution Failure: ' . $e->getMessage()
        ]);
    }
    exit;
}

// FALLBACK: UNAUTHORIZED ACCESS
header('Content-Type: application/json', true, 403);
echo json_encode(['error' => 'Sovereign Bridge: Connection logic missing.']);
