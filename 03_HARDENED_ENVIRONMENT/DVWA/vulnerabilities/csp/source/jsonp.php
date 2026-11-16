<?php

header('Content-Type: application/json; charset=UTF-8');
header('X-Content-Type-Options: nosniff');

http_response_code(400);
echo json_encode(['error' => 'JSONP callbacks are not supported.']);

?>
