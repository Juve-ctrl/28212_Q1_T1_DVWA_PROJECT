<?php

/**
 * Fail-closed Google reCAPTCHA verification.
 * Verification succeeds only when Google returns valid JSON
 * containing the boolean value success=true.
 */
function recaptcha_check_answer($key, $response) {
    if (!is_string($key) || trim($key) === '') {
        return false;
    }

    if (!is_string($response) || trim($response) === '') {
        return false;
    }

    $url = 'https://www.google.com/recaptcha/api/siteverify';

    $payload = http_build_query([
        'secret' => $key,
        'response' => $response,
    ]);

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => $payload,
            'timeout' => 5,
            'ignore_errors' => false,
        ],
        'ssl' => [
            'verify_peer' => true,
            'verify_peer_name' => true,
        ],
    ];

    try {
        $context = stream_context_create($options);
        $result = @file_get_contents(
            $url,
            false,
            $context
        );

        if ($result === false) {
            return false;
        }

        $decoded = json_decode($result);

        return is_object($decoded)
            && isset($decoded->success)
            && $decoded->success === true;
    } catch (Throwable $exception) {
        error_log('CAPTCHA verification failed: ' . $exception->getMessage());
        return false;
    }
}

function recaptcha_get_html($pubKey) {
    $safeKey = htmlspecialchars(
        (string)$pubKey,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );

    return "
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <br />
        <div class='g-recaptcha' data-theme='dark' data-sitekey='{$safeKey}'></div>
    ";
}
