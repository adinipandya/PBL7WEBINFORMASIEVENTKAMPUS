<?php
session_start();
require_once __DIR__ . "/../../config/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: ../login.php");
  exit;
}

$username = trim($_POST["username"] ?? "");
$password = trim($_POST["password"] ?? "");

if ($username === "" || $password === "") {
  header("Location: ../login.php?err=empty");
  exit;
}

// ambil user dari DB
$stmt = $conn->prepare("SELECT id, username, password_hash, role, is_active FROM users WHERE username = ? LIMIT 1");
$stmt->bind_param("s", $username);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
  header("Location: ../login.php?err=invalid");
  exit;
}

$user = $res->fetch_assoc();

if ((int)$user["is_active"] !== 1) {
  header("Location: ../login.php?err=inactive");
  exit;
}

// verifikasi password bcrypt
if (!password_verify($password, $user["password_hash"])) {
  header("Location: ../login.php?err=invalid");
  exit;
}

// sukses: set session
$_SESSION["user_id"] = (int)$user["id"];
$_SESSION["username"] = $user["username"];
$_SESSION["role"] = $user["role"];
$_SESSION["logged_in"] = true;

// redirect ke admin
header("Location: ../index.php");
exit;
