<?php

define('ALGO', 'aes-256-gcm');

function get_crypto_key(): string {
    $configuredKey = getenv('DVWA_CRYPTO_KEY');

    if (!is_string($configuredKey) ||
        !preg_match('/^[a-fA-F0-9]{64}$/', $configuredKey)) {
        throw new RuntimeException('Encryption key is unavailable.');
    }

    $key = hex2bin($configuredKey);

    if ($key === false || strlen($key) !== 32) {
        throw new RuntimeException('Encryption key configuration is invalid.');
    }

    return $key;
}

function encrypt($plaintext, $iv) {
    if (!is_string($plaintext) || !is_string($iv) || strlen($iv) !== 12) {
        throw new InvalidArgumentException('Invalid encryption input.');
    }

    $tag = '';

    $encrypted = openssl_encrypt(
        $plaintext,
        ALGO,
        get_crypto_key(),
        OPENSSL_RAW_DATA,
        $iv,
        $tag,
        '',
        16
    );

    if ($encrypted === false || strlen($tag) !== 16) {
        throw new RuntimeException('Encryption failed.');
    }

    return $encrypted . $tag;
}

function decrypt($ciphertext, $iv) {
    if (!is_string($ciphertext) ||
        !is_string($iv) ||
        strlen($iv) !== 12 ||
        strlen($ciphertext) < 17) {
        throw new InvalidArgumentException('Invalid encrypted token.');
    }

    $tag = substr($ciphertext, -16);
    $encrypted = substr($ciphertext, 0, -16);

    $plaintext = openssl_decrypt(
        $encrypted,
        ALGO,
        get_crypto_key(),
        OPENSSL_RAW_DATA,
        $iv,
        $tag,
        ''
    );

    if ($plaintext === false) {
        throw new RuntimeException('Token authentication failed.');
    }

    return $plaintext;
}

function create_token() {
    $iv = random_bytes(12);
    $encrypted = encrypt('userid:2', $iv);

    return json_encode([
        'token' => base64_encode($encrypted),
        'iv' => base64_encode($iv)
    ], JSON_THROW_ON_ERROR);
}

function check_token($data) {
    $users = [
        1 => ['name' => 'Geoffery', 'level' => 'admin'],
        2 => ['name' => 'Bungle', 'level' => 'user'],
        3 => ['name' => 'Zippy', 'level' => 'user'],
        4 => ['name' => 'George', 'level' => 'user']
    ];

    try {
        if (!is_string($data) || strlen($data) > 4096) {
            throw new InvalidArgumentException('Invalid token request.');
        }

        $decoded = json_decode($data, true, 8, JSON_THROW_ON_ERROR);

        if (!is_array($decoded) ||
            !isset($decoded['token'], $decoded['iv']) ||
            !is_string($decoded['token']) ||
            !is_string($decoded['iv'])) {
            throw new InvalidArgumentException('Invalid token format.');
        }

        $ciphertext = base64_decode($decoded['token'], true);
        $iv = base64_decode($decoded['iv'], true);

        if ($ciphertext === false || $iv === false ||
            base64_encode($ciphertext) !== $decoded['token'] ||
            base64_encode($iv) !== $decoded['iv']) {
            throw new InvalidArgumentException('Invalid token encoding.');
        }

        $plaintext = decrypt($ciphertext, $iv);

        if (!preg_match('/^userid:([1-4])$/D', $plaintext, $matches)) {
            throw new InvalidArgumentException('Invalid token identity.');
        }

        $user = $users[(int)$matches[1]];

        return json_encode([
            'status' => 200,
            'user' => $user['name'],
            'level' => $user['level']
        ], JSON_THROW_ON_ERROR);

    } catch (Throwable $exception) {
        error_log('Cryptography token validation failed: ' . $exception->getMessage());

        return json_encode([
            'status' => 526,
            'message' => 'Invalid or unauthenticated token.'
        ]);
    }
}
