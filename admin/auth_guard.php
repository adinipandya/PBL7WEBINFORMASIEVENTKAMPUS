<?php
session_start();

if (empty($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
  header("Location: ../login.php");
  exit;
}

if (($_SESSION["role"] ?? "") !== "admin") {
  header("Location: ../login.php");
  exit;
}
