<?php
// [TIMESTAMP: 2026-03-30] - GAC Session Kill-Switch logout.php
session_start(); // 1. Unset all session variables
$_SESSION = array();
// 2. Destroy the session cookie in the browser
if (ini_get("session.use_cookies")) { $params = session_get_cookie_params();
                                      setcookie(session_name(), '', time() - 42000,
                                      $params["path"], $params["domain"],
                                      $params["secure"], $params["httponly"]);}
// 3. Destroy the server-side session
session_destroy();
// 4. Return to the Login Gate
header("Location: index.php");
exit();
?>
