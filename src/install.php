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
        `title` varchar(255) NOT NULL,
        `content` text DEFAULT NULL,
        `created_at` datetime NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
];

foreach ($queries as $name => $sql) {
    if (!$connection->query($sql)) {
        echo "Error creating $name: " . $connection->error . "<br>";
    }
}

$insertUser = "REPLACE INTO UsersTable (username, password) VALUES ('admin', 'admin')";
$insertNotes = "REPLACE INTO NotesTable (title, content) VALUES 
('title 1', 'content 1'),('title 2', 'content 2'),('title 3', 'content 3'),('title 4', 'content 4'),('title 5', 'content 5'),
('title 6', 'content 6'),('title 7', 'content 7'),('title 8', 'content 8'),('title 9', 'content 9'),('title 10', 'content 10')";


if (!$connection -> query($insertUser)) {
    echo "Error inserting default user: " . $connection->error . "<br>";
}

if (!$connection -> query($insertNotes)) {
    echo "Error inserting default notes: " . $connection->error . "<br>";
}
?>
