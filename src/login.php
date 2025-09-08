<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

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
            header("Location: index.php");
            exit;
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-6 rounded shadow w-full max-w-sm">
        <h1 class="text-xl font-bold mb-4 text-center">Login</h1>

        <?php if (!empty($error)): ?>
            <p class="text-red-500 mb-3"><?= $error ?></p>
        <?php endif; ?>

        <form method="post" class="space-y-3">
            <div>
                <label class="block mb-1">Username:</label>
                <input type="text" name="username" required
                       class="w-full border px-2 py-1 rounded focus:ring focus:ring-blue-300">
            </div>

            <div>
                <label class="block mb-1">Password:</label>
                <input type="password" name="password" required
                       class="w-full border px-2 py-1 rounded focus:ring focus:ring-blue-300">
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-1 rounded hover:bg-blue-600">
                Login
            </button>
        </form>
    </div>
</body>
</html>
