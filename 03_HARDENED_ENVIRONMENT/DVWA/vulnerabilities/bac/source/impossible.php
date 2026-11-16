<?php

$html = '';
$currentUsername = dvwaCurrentUser();
$currentUserId = 0;
$currentRole = '';

try {
    if (!is_string($currentUsername) || $currentUsername === '') {
        throw new RuntimeException('Unauthenticated user');
    }

    $db = $GLOBALS['___mysqli_ston'];

    $stmt = mysqli_prepare(
        $db,
        'SELECT user_id, role FROM users WHERE user = ? LIMIT 1'
    );

    if (!$stmt) {
        throw new RuntimeException('Database query failed');
    }

    mysqli_stmt_bind_param($stmt, 's', $currentUsername);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $identity = $result ? mysqli_fetch_assoc($result) : null;
    mysqli_stmt_close($stmt);

    if (!$identity || (int)$identity['user_id'] < 1) {
        throw new RuntimeException('User identity not found');
    }

    $currentUserId = (int)$identity['user_id'];
    $currentRole = (string)$identity['role'];

    // Always derive the session user ID from the authenticated database identity.
    $_SESSION['user_id'] = $currentUserId;

    if (isset($_GET['action'], $_GET['user_id'])) {
        $requestedId = $_GET['user_id'];

        if (!is_string($requestedId) || !ctype_digit($requestedId) ||
            filter_var($requestedId, FILTER_VALIDATE_INT) === false ||
            (int)$requestedId < 1) {
            http_response_code(400);
            $html .= '<p>Invalid user ID.</p>';
        } else {
            $targetId = (int)$requestedId;
            $isAdmin = ($currentUsername === 'admin' || $currentRole === 'admin');

            if (!$isAdmin && $targetId !== $currentUserId) {
                http_response_code(403);
                $html .= '<p>Access denied. You can only view your own profile.</p>';
            } else {
                $profileStmt = mysqli_prepare(
                    $db,
                    'SELECT user_id, first_name, last_name, avatar
                     FROM users WHERE user_id = ? LIMIT 1'
                );

                if (!$profileStmt) {
                    throw new RuntimeException('Profile query failed');
                }

                mysqli_stmt_bind_param($profileStmt, 'i', $targetId);
                mysqli_stmt_execute($profileStmt);
                $profileResult = mysqli_stmt_get_result($profileStmt);
                $profile = $profileResult
                    ? mysqli_fetch_assoc($profileResult)
                    : null;
                mysqli_stmt_close($profileStmt);

                if (!$profile) {
                    http_response_code(404);
                    $html .= '<p>User not found.</p>';
                } else {
                    $safeId = htmlspecialchars((string)$profile['user_id'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $safeFirst = htmlspecialchars((string)$profile['first_name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $safeLast = htmlspecialchars((string)$profile['last_name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $safeAvatar = htmlspecialchars((string)$profile['avatar'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

                    $html .= '<div class="profile-info">';
                    $html .= '<h3>User Profile</h3>';
                    $html .= '<p>User ID: ' . $safeId . '</p>';
                    $html .= '<p>Name: ' . $safeFirst . ' ' . $safeLast . '</p>';
                    $html .= '<p>Avatar: ' . $safeAvatar . '</p>';
                    $html .= '</div>';
                }
            }
        }
    }
} catch (Throwable $e) {
    error_log('Broken access control profile lookup failed: ' . $e->getMessage());
    http_response_code(500);
    $html .= '<p>Unable to retrieve profile.</p>';
}
?>
