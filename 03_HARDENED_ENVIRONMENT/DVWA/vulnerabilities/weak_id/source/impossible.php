<?php

$html = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $https = (
        isset($_SERVER['HTTPS']) &&
        $_SERVER['HTTPS'] !== '' &&
        strtolower($_SERVER['HTTPS']) !== 'off'
    );

    if (!$https) {
        $html = '<pre>Secure session cookie requires HTTPS.</pre>';
    } else {
        try {
            $cookie_value = bin2hex(random_bytes(20));

            setcookie("dvwaSession", $cookie_value, [
                'expires' => time() + 3600,
                'path' => '/vulnerabilities/weak_id/',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);

            $html = '<pre>Secure session identifier generated.</pre>';
        } catch (Throwable $e) {
            error_log('Weak Session ID generation failed: ' . $e->getMessage());
            $html = '<pre>Session identifier generation failed.</pre>';
        }
    }
}

?>
