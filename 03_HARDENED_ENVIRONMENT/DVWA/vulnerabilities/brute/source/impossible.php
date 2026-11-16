<?php

if (
    isset($_POST['Login']) &&
    isset($_POST['username']) &&
    isset($_POST['password'])
) {
    // Preserve DVWA's CSRF protection.
    checkToken(
        $_REQUEST['user_token'] ?? '',
        $_SESSION['session_token'],
        'index.php'
    );

    // Normalise the username and retain password compatibility.
    $user = trim((string)$_POST['username']);
    $pass = (string)$_POST['password'];

    $user = mb_substr($user, 0, 15, 'UTF-8');

    // Do not trust arbitrary forwarded IP headers.
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

    if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
        $ip = 'unknown';
    }

    // Hash the username so raw usernames are not stored in the tracker.
    $usernameHash = hash('sha256', mb_strtolower($user, 'UTF-8'));

    $maxAttempts = 5;
    $windowMinutes = 15;
    $lockoutMinutes = 15;

    $genericFailure = '<pre>Username and/or password incorrect. Please try again later if access is temporarily restricted.</pre>';

    try {
        $db->beginTransaction();

        // Create the tracking identity if it does not already exist.
        $insert = $db->prepare(
            'INSERT IGNORE INTO brute_force_attempts
             (username_hash, ip_address, attempts, window_started_at, locked_until, last_attempt_at)
             VALUES (:username_hash, :ip_address, 0, UTC_TIMESTAMP(), NULL, UTC_TIMESTAMP())'
        );

        $insert->execute([
            ':username_hash' => $usernameHash,
            ':ip_address' => $ip
        ]);

        // Lock this identity's tracking row against concurrent updates.
        $trackerQuery = $db->prepare(
            'SELECT attempts, window_started_at, locked_until
             FROM brute_force_attempts
             WHERE username_hash = :username_hash
               AND ip_address = :ip_address
             FOR UPDATE'
        );

        $trackerQuery->execute([
            ':username_hash' => $usernameHash,
            ':ip_address' => $ip
        ]);

        $tracker = $trackerQuery->fetch(PDO::FETCH_ASSOC);

        if (!$tracker) {
            throw new RuntimeException('Attempt tracking record unavailable.');
        }

        $now = time();
        $lockedUntil = $tracker['locked_until']
            ? strtotime($tracker['locked_until'] . ' UTC')
            : false;

        // An active lockout is never extended by another request.
        if ($lockedUntil !== false && $now < $lockedUntil) {
            $db->commit();
            $html .= $genericFailure;
        } else {
            // Expired lockouts and old attempt windows are reset.
            $windowStarted = strtotime($tracker['window_started_at'] . ' UTC');

            if (
                ($lockedUntil !== false && $now >= $lockedUntil) ||
                $windowStarted === false ||
                $now >= ($windowStarted + ($windowMinutes * 60))
            ) {
                $reset = $db->prepare(
                    'UPDATE brute_force_attempts
                     SET attempts = 0,
                         window_started_at = UTC_TIMESTAMP(),
                         locked_until = NULL,
                         last_attempt_at = UTC_TIMESTAMP()
                     WHERE username_hash = :username_hash
                       AND ip_address = :ip_address'
                );

                $reset->execute([
                    ':username_hash' => $usernameHash,
                    ':ip_address' => $ip
                ]);

                $tracker['attempts'] = 0;
            }

            // Check the account using a prepared statement.
            $loginQuery = $db->prepare(
                'SELECT user_id, user, password, avatar
                 FROM users
                 WHERE user = :user
                 LIMIT 1'
            );

            $loginQuery->execute([':user' => $user]);
            $account = $loginQuery->fetch(PDO::FETCH_ASSOC);

            // Preserve the existing DVWA password format.
            $passwordHash = md5($pass);

            $validLogin = (
                $account &&
                hash_equals($account['password'], $passwordHash)
            );

            if ($validLogin) {
                // Clear the attempt record after successful authentication.
                $clear = $db->prepare(
                    'DELETE FROM brute_force_attempts
                     WHERE username_hash = :username_hash
                       AND ip_address = :ip_address'
                );

                $clear->execute([
                    ':username_hash' => $usernameHash,
                    ':ip_address' => $ip
                ]);

                $db->commit();

                $avatar = htmlspecialchars(
                    $account['avatar'],
                    ENT_QUOTES | ENT_SUBSTITUTE,
                    'UTF-8'
                );

                $safeUser = htmlspecialchars(
                    $account['user'],
                    ENT_QUOTES | ENT_SUBSTITUTE,
                    'UTF-8'
                );

                $html .= "<p>Welcome to the password protected area <em>{$safeUser}</em></p>";
                $html .= "<img src=\"{$avatar}\" alt=\"User avatar\" />";
            } else {
                $newAttempts = ((int)$tracker['attempts']) + 1;

                $newLockout = $newAttempts >= $maxAttempts
                    ? gmdate('Y-m-d H:i:s', $now + ($lockoutMinutes * 60))
                    : null;

                $update = $db->prepare(
                    'UPDATE brute_force_attempts
                     SET attempts = :attempts,
                         locked_until = :locked_until,
                         last_attempt_at = UTC_TIMESTAMP()
                     WHERE username_hash = :username_hash
                       AND ip_address = :ip_address'
                );

                $update->execute([
                    ':attempts' => $newAttempts,
                    ':locked_until' => $newLockout,
                    ':username_hash' => $usernameHash,
                    ':ip_address' => $ip
                ]);

                $db->commit();

                $html .= $genericFailure;
            }
        }
    } catch (Throwable $exception) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }

        // Do not expose database or authentication details to visitors.
        error_log('DVWA Brute Force protection error: ' . $exception->getMessage());

        $html .= '<pre>Login could not be processed. Please try again later.</pre>';
    }
}

// Generate the next DVWA CSRF token.
generateSessionToken();
