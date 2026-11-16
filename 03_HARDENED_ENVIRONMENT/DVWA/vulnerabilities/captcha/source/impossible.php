<?php

if (isset($_POST['Change'])) {
    // Require a valid CSRF token before processing the request.
    checkToken(
        $_REQUEST['user_token'] ?? '',
        $_SESSION['session_token'] ?? '',
        'index.php'
    );

    $hide_form = true;

    $publicKey = $_DVWA['recaptcha_public_key'] ?? '';
    $privateKey = $_DVWA['recaptcha_private_key'] ?? '';
    $captchaResponse = $_POST['g-recaptcha-response'] ?? '';

    // Fail closed when the external CAPTCHA service is not configured.
    if (
        !is_string($publicKey) ||
        trim($publicKey) === '' ||
        !is_string($privateKey) ||
        trim($privateKey) === ''
    ) {
        $html .= '<pre>CAPTCHA verification is unavailable. Password change rejected.</pre>';
        $hide_form = false;
    } elseif (
        !is_string($captchaResponse) ||
        trim($captchaResponse) === '' ||
        recaptcha_check_answer($privateKey, $captchaResponse) !== true
    ) {
        $html .= '<pre>CAPTCHA verification failed. Password change rejected.</pre>';
        $hide_form = false;
    } else {
        $currentPassword = $_POST['password_current'] ?? '';
        $newPassword = $_POST['password_new'] ?? '';
        $confirmPassword = $_POST['password_conf'] ?? '';

        if (
            !is_string($currentPassword) ||
            !is_string($newPassword) ||
            !is_string($confirmPassword) ||
            $currentPassword === '' ||
            $newPassword === '' ||
            strlen($newPassword) > 128 ||
            !hash_equals($newPassword, $confirmPassword)
        ) {
            $html .= '<pre>Invalid password input or new passwords do not match.</pre>';
            $hide_form = false;
        } else {
            // MD5 is retained only for compatibility with DVWA's
            // existing users.password schema. It is not suitable
            // for production password storage.
            $currentHash = md5($currentPassword);
            $newHash = md5($newPassword);
            $username = dvwaCurrentUser();

            // Verify the current password with a prepared statement.
            $statement = $db->prepare(
                'SELECT password FROM users
                 WHERE user = :user AND password = :password
                 LIMIT 1'
            );

            $statement->execute([
                ':user' => $username,
                ':password' => $currentHash,
            ]);

            $storedPassword = $statement->fetchColumn();

            if ($storedPassword !== false) {
                $update = $db->prepare(
                    'UPDATE users SET password = :password
                     WHERE user = :user'
                );

                $update->execute([
                    ':password' => $newHash,
                    ':user' => $username,
                ]);

                $html .= '<pre>Password Changed.</pre>';
            } else {
                $html .= '<pre>Current password is incorrect. Password change rejected.</pre>';
                $hide_form = false;
            }
        }
    }
}

// Generate a fresh DVWA session token.
generateSessionToken();

?>
