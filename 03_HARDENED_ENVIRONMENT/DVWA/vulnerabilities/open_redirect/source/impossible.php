<?php

$html = '';

$allowedDestinations = [
    '1' => 'info.php?id=1',
    '2' => 'info.php?id=2',
];

$requestedDestination = $_GET['redirect'] ?? '';

if (!is_string($requestedDestination) ||
    !array_key_exists($requestedDestination, $allowedDestinations)) {
    http_response_code(400);
    $html .= '<p>Redirect rejected: destination not permitted.</p>';
} else {
    $destination = $allowedDestinations[$requestedDestination];

    header('Cache-Control: no-store');
    header('Location: ' . $destination, true, 303);
    exit;
}
?>
