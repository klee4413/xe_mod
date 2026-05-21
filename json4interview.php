<?php
//session_start();
//header('Content-Type: application/json');
//echo isset($_SESSION['interviewee_json']) ? $_SESSION['interviewee_json'] : json_encode(['error' => 'No session found']);
// 1. Initialize GAC Session Logic
session_start();
header('Content-Type: application/json');
// 2. The Logic Gate: Ensure the Interviewee JSON exists
$interviewee_json = isset($_SESSION['interviewee_json']) ? $_SESSION['interviewee_json'] : 'null';
// 3. Generate the Surgical Timestamp for this session
$current_timestamp = date("Y-m-d H:i:s");
?>