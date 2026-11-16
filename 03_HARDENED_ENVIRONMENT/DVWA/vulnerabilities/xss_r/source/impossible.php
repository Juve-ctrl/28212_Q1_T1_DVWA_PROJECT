<?php

if (
    array_key_exists('name', $_GET) &&
    $_GET['name'] !== ''
) {
    // Reject arrays and non-string input.
    if (!is_string($_GET['name']) || strlen($_GET['name']) > 100) {
        $html .= '<pre>Invalid input.</pre>';
    } else {
        // Preserve DVWA anti-CSRF validation.
        checkToken(
            $_REQUEST['user_token'] ?? '',
            $_SESSION['session_token'] ?? '',
            'index.php'
        );

        // Encode all HTML-sensitive characters.
        $name = htmlspecialchars(
            $_GET['name'],
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );

        $html .= "<pre>Hello {$name}</pre>";
    }
}

generateSessionToken();

?>
