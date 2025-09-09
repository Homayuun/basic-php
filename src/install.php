<?php
include 'connect.php';

$queries = [
    "UsersTable" => "CREATE TABLE IF NOT EXISTS `UsersTable` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `username` varchar(50) NOT NULL,
        `password` varchar(255) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        UNIQUE KEY `username` (`username`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    
    "NotesTable" => "CREATE TABLE IF NOT EXISTS `NotesTable` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `title` varchar(255) NOT NULL,
        `content` text DEFAULT NULL,
        `created_at` datetime NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        FOREIGN KEY (`user_id`) REFERENCES `UsersTable`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
];

foreach ($queries as $name => $sql) {
    if (!$connection->query($sql)) {
        echo "Error creating $name: " . $connection->error . "<br>";
    }
}

$insertUser = "INSERT INTO UsersTable (username, password) VALUES ('admin', 'admin') ON DUPLICATE KEY UPDATE username=username";
$insertSecondUser = "INSERT INTO UsersTable (username, password) VALUES ('admin2', 'admin2') ON DUPLICATE KEY UPDATE username=username";

if (!$connection->query($insertUser)) {
    die("Error inserting admin user: " . $connection->error);
}
if (!$connection->query($insertSecondUser)) {
    die("Error inserting admin2 user: " . $connection->error);
}

$result = $connection->query("SELECT id FROM UsersTable WHERE username='admin'");
if ($result && $user = $result->fetch_assoc()) {
    $user_id = $user['id'];
} else {
    die("Error fetching admin user ID");
}

$insertNotes = "INSERT INTO NotesTable (user_id, title, content) VALUES
($user_id, 'title 1', 'content 1'),
($user_id, 'title 2', 'content 2'),
($user_id, 'title 3', 'content 3'),
($user_id, 'title 4', 'content 4'),
($user_id, 'title 5', 'content 5'),
($user_id, 'title 6', 'content 6'),
($user_id, 'title 7', 'content 7'),
($user_id, 'title 8', 'content 8'),
($user_id, 'title 9', 'content 9'),
($user_id, 'title 10', 'content 10')";

if (!$connection -> query($insertUser)) {
    echo "Error inserting default user: " . $connection->error . "<br>";
}

if (!$connection -> query($insertNotes)) {
    echo "Error inserting default notes: " . $connection->error . "<br>";
}
?>
