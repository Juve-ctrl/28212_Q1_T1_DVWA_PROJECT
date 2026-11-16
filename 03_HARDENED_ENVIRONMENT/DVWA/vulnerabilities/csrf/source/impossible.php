<?php

// Hardened CSRF password-change handler.
// This handler accepts POST requests only.

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Change'])) {

    $submittedToken = (string)($_POST['csrf_token'] ?? '');
    $sessionToken = $_SESSION['csrf_password_change_token'] ?? '';

    // Validate the dedicated CSRF token.
    if (
        !is_string($sessionToken) ||
        $sessionToken === '' ||
        $submittedToken === '' ||
        !hash_equals($sessionToken, $submittedToken)
    ) {
        http_response_code(403);
        $html .= '<pre>Request rejected: invalid security token.</pre>';
    } else {

        // Read and validate submitted values.
        $passCurr = (string)($_POST['password_current'] ?? '');
        $passNew = (string)($_POST['password_new'] ?? '');
        $passConf = (string)($_POST['password_conf'] ?? '');

        if (
            $passCurr === '' ||
            $passNew === '' ||
            $passConf === '' ||
            !hash_equals($passNew, $passConf)
        ) {
            $html .= '<pre>Passwords did not match or required fields were missing.</pre>';
        } else {

            try {
                $currentUser = dvwaCurrentUser();

                // Verify the current password using a prepared statement.
                $currentHash = md5($passCurr);

                $select = $db->prepare(
                    'SELECT password FROM users WHERE user = :user LIMIT 1'
                );

                $select->execute([':user' => $currentUser]);
                $storedHash = $select->fetchColumn();

                if (
                    $storedHash !== false &&
                    hash_equals((string)$storedHash, $currentHash)
                ) {
                    // Retain MD5 only for compatibility with this DVWA lab schema.
                    $newHash = md5($passNew);

                    $update = $db->prepare(
                        'UPDATE users SET password = :password WHERE user = :user'
                    );

                    $update->execute([
                        ':password' => $newHash,
                        ':user' => $currentUser
                    ]);

                    $html .= '<pre>Password Changed.</pre>';
                } else {
                    $html .= '<pre>Passwords did not match or current password incorrect.</pre>';
                }

            } catch (PDOException $exception) {
                error_log('DVWA CSRF password update failed: ' . $exception->getMessage());
                http_response_code(500);
                $html .= '<pre>Password change could not be completed.</pre>';
            }
        }
    }

    // Rotate the dedicated token after every POST attempt.
    $_SESSION['csrf_password_change_token'] = bin2hex(random_bytes(32));
}

// Ensure the form has a dedicated token.
if (
    !isset($_SESSION['csrf_password_change_token']) ||
    !is_string($_SESSION['csrf_password_change_token']) ||
    strlen($_SESSION['csrf_password_change_token']) !== 64
) {
    $_SESSION['csrf_password_change_token'] = bin2hex(random_bytes(32));
}

?>

