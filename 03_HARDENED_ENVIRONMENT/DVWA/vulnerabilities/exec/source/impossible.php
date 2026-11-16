<?php

if (isset($_POST['Submit'])) {

    // Validate the DVWA CSRF token.
    checkToken(
        $_REQUEST['user_token'] ?? '',
        $_SESSION['session_token'],
        'index.php'
    );

    // Accept only a valid IPv4 address.
    $target = trim((string)($_POST['ip'] ?? ''));

    if (filter_var($target, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
        $html .= '<pre>ERROR: You have entered an invalid IP.</pre>';
    } else {

        // Use a fixed executable and separate arguments.
        $command = ['/usr/bin/ping', '-c', '4', $target];

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w']
        ];

        $process = proc_open(
            $command,
            $descriptors,
            $pipes,
            null,
            null,
            ['bypass_shell' => true]
        );

        if (is_resource($process)) {
            fclose($pipes[0]);

            $output = stream_get_contents($pipes[1]);
            $error = stream_get_contents($pipes[2]);

            fclose($pipes[1]);
            fclose($pipes[2]);

            $exitCode = proc_close($process);

            if ($exitCode === 0) {
                $html .= '<pre>' .
                    htmlspecialchars($output, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') .
                    '</pre>';
            } else {
                error_log('DVWA ping operation failed: ' . $error);
                $html .= '<pre>Ping operation failed.</pre>';
            }
        } else {
            $html .= '<pre>Ping operation could not be started.</pre>';
        }
    }
}

// Generate the next DVWA CSRF token.
generateSessionToken();
