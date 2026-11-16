<?php

require_once __DIR__ . '/token_library_impossible.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 405,
        'message' => 'Method not supported.'
    ]);
    exit;
}

$contentType = $_SERVER['CONTENT_TYPE'] ?? '';

if (!preg_match('/^application\/json(?:\s*;|$)/i', $contentType)) {
    http_response_code(415);
    echo json_encode([
        'status' => 415,
        'message' => 'Content type must be application/json.'
    ]);
    exit;
}

$body = file_get_contents('php://input', false, null, 0, 4097);

if ($body === false || strlen($body) > 4096) {
    http_response_code(413);
    echo json_encode([
        'status' => 413,
        'message' => 'Request is too large.'
    ]);
    exit;
}

echo check_token($body);
exit;
