<?php
// GAC SESSION PERSISTENCE PROTOCOL
// This script is the server-side "Receiver" for the Pulse.
session_start();

// 1. TEMPORAL SYNCHRONIZATION (PST)
date_default_timezone_set('America/Los_Angeles');

// 2. IDENTITY VERIFICATION
// We check if the session is still grounded.
$is_alive = isset($_SESSION['user_id']) && $_SESSION['user_id'] !== 'GAC-UNKNOWN';

// 3. EMIT JSON RESPONSE
header('Content-Type: application/json');

if ($is_alive) {
    // Touching the session updates its 'Last Modified' time on the server disk.
    echo json_encode([
        "status" => "alive",
        "timestamp" => date('Y-m-d H:i:s'),
        "identity" => ($_SESSION['first_name'] ?? 'Session-x') . " " . ($_SESSION['last_name'] ?? 'Stopped')
    ]);
} else {
    // Signal the browser that the Neural Link is severed.
    http_response_code(401);
    echo json_encode([
        "status" => "expired",
        "message" => "Identity Dissonance: Session lost."
    ]);
}
exit();