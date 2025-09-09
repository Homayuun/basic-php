<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$currentUserId = $_SESSION['user_id'];
$currentUsername = $_SESSION['username'];