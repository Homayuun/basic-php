<?php
session_start();
include 'connect.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!$username || !$password) {
            exit("Username and password are required.");
        }

        $sql = "SELECT * FROM UsersTable WHERE username = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                exit("OK");
            } else {
                exit("Incorrect password.");
            }
        } else {
            exit("User not found.");
        }
        break;

    default:
        exit("Invalid action.");
}
