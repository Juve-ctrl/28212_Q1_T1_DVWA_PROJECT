<?php

define('DVWA_WEB_PAGE_TO_ROOT', '../../');
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

header('Content-Type: application/json; charset=UTF-8');
header('X-Content-Type-Options: nosniff');

dvwaDatabaseConnect();

function authBypassFail($message, $status = 400) {
    http_response_code($status);
    echo json_encode(['result' => 'fail', 'error' => $message]);
    exit;
}

if (empty($_SESSION['username']) || dvwaCurrentUser() !== 'admin') {
    authBypassFail('Access denied', 403);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    authBypassFail('Only POST requests are accepted', 405);
}

try {
    $data = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);

    if (!is_array($data) ||
        !isset($data['id'], $data['first_name'], $data['surname']) ||
        !is_int($data['id']) ||
        $data['id'] < 1 ||
        !is_string($data['first_name']) ||
        !is_string($data['surname'])) {
        authBypassFail('Invalid request data');
    }

    $firstName = trim($data['first_name']);
    $surname = trim($data['surname']);

    if ($firstName === '' || $surname === '' ||
        mb_strlen($firstName) > 30 || mb_strlen($surname) > 30) {
        authBypassFail('Invalid name fields');
    }

    $db = $GLOBALS['___mysqli_ston'];
    $statement = mysqli_prepare(
        $db,
        'UPDATE users SET first_name = ?, last_name = ? WHERE user_id = ?'
    );

    if (!$statement) {
        throw new RuntimeException('Database operation failed');
    }

    mysqli_stmt_bind_param($statement, 'ssi', $firstName, $surname, $data['id']);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);

    echo json_encode(['result' => 'ok']);
} catch (Throwable $e) {
    error_log('Authorisation bypass update failed: ' . $e->getMessage());
    authBypassFail('Request could not be processed', 500);
}
