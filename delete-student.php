<?php
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($id > 0 && deleteStudent($id)) {
    header('Location: index.php?deleted=' . urlencode(studentNumber($id)));
    exit;
}

header('Location: index.php?delete_error=1');
exit;
