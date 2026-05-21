<?php
// [TIMESTAMP: 2026-03-28] - GAC Student Identity Agent: get_student_session.php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    echo json_encode([
        "id" => $_SESSION['user_id'],
        "first_name" => $_SESSION['first_name'],
        "last_name" => $_SESSION['last_name'],
        "email" => $_SESSION['email'],
        "status" => "Authorized"
    ]);
} else {
    echo json_encode(["status" => "Student Session Unauthorized"]);
}
?>