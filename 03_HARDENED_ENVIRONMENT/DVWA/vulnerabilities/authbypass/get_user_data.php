<?php

define('DVWA_WEB_PAGE_TO_ROOT', '../../');
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

header('Content-Type: application/json; charset=UTF-8');
header('X-Content-Type-Options: nosniff');

dvwaDatabaseConnect();

if (empty($_SESSION['username']) || dvwaCurrentUser() !== 'admin') {
    http_response_code(403);
    echo json_encode(['result' => 'fail', 'error' => 'Access denied']);
    exit;
}

try {
    $db = $GLOBALS['___mysqli_ston'];
    $statement = mysqli_prepare(
        $db,
        'SELECT user_id, first_name, last_name FROM users ORDER BY user_id'
    );

    if (!$statement || !mysqli_stmt_execute($statement)) {
        throw new RuntimeException('Database operation failed');
    }

    $result = mysqli_stmt_get_result($statement);
    $users = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = [
            'user_id' => (int)$row['user_id'],
            'first_name' => $row['first_name'],
            'surname' => $row['last_name']
        ];
    }

    mysqli_stmt_close($statement);
    echo json_encode(['result' => 'ok', 'users' => $users]);
} catch (Throwable $e) {
    error_log('Authorisation bypass retrieval failed: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['result' => 'fail', 'error' => 'Request could not be processed']);
}
