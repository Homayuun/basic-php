<?php
include 'auth.php';
include 'connect.php';

header("Content-Type: application/json");

$action = $_GET['action'] ?? $_POST['action'] ?? '';

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

switch ($action) {
    case 'load':
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $perPage = isset($_GET['perPage']) ? max(1, intval($_GET['perPage'])) : 5;
        $offset = ($page - 1) * $perPage;

        $countStmt = $connection->prepare("SELECT COUNT(*) AS total FROM NotesTable WHERE user_id = ?");
        $countStmt->bind_param("i", $userId);
        $countStmt->execute();
        $countRes = $countStmt->get_result();
        $totalNotes = 0;
        if ($countRes) {
            $r = $countRes->fetch_assoc();
            $totalNotes = intval($r['total'] ?? 0);
        }

        $totalPages = ($perPage > 0) ? (int)ceil($totalNotes / $perPage) : 0;
        if ($totalPages < 1) $totalPages = 1;

        $sql = "SELECT id, title, content, created_at FROM NotesTable WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("iii", $userId, $perPage, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $notes = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $notes[] = $row;
            }
        }

        echo json_encode([
            'success' => true,
            'notes' => $notes,
            'totalNotes' => $totalNotes,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'perPage' => $perPage
        ]);
        exit;
        break;

    case 'create':
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        if ($title && $content) {
            $stmt = $connection->prepare("INSERT INTO NotesTable (title, content, user_id) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $title, $content, $userId);
            if ($stmt->execute()) {
                exit("OK");
            } else {
                echo "Error: " . $connection->error;
            }
        } else {
            echo "Title and content are required.";
        }
        exit;
        break;

    case 'delete':
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid id']);
            exit;
        }
        $stmt = $connection->prepare("DELETE FROM NotesTable WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $userId);
        $ok = $stmt->execute();
        echo json_encode(['success' => (bool)$ok]);
        exit;
        break;

    case 'edit':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0) {
            echo "Invalid ID";
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title   = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');

            if (!empty($title) && !empty($content)) {
                $updateSQL = "UPDATE NotesTable SET title = ?, content = ? WHERE id = ? AND user_id = ?";
                $stmt = $connection->prepare($updateSQL);
                $stmt->bind_param("ssii", $title, $content, $id, $userId);

                if ($stmt->execute()) {
                    exit("OK");
                } else {
                    exit("Error updating: " . $connection->error);
                }
            } else {
                echo "Title and content are required.";
                exit;
            }
        }

        $selectSQL = "SELECT * FROM NotesTable WHERE id = ? AND user_id = ?";
        $stmt = $connection->prepare($selectSQL);
        $stmt->bind_param("ii", $id, $userId);
        $stmt->execute();
        $note = $stmt->get_result()->fetch_assoc();

        if (!$note) {
            echo "Note not found";
            exit;
        }

        echo json_encode(['success' => true, 'note' => $note]);
        exit;
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit;
        break;
}
