<?php

if (isset($_POST['btnSign'])) {
    checkToken(
        $_REQUEST['user_token'] ?? '',
        $_SESSION['session_token'] ?? '',
        'index.php'
    );

    $message = $_POST['mtxMessage'] ?? null;
    $name = $_POST['txtName'] ?? null;

    if (
        !is_string($name) ||
        !is_string($message) ||
        trim($name) === '' ||
        trim($message) === '' ||
        strlen($name) > 10 ||
        strlen($message) > 50
    ) {
        $html .= '<pre>Invalid guestbook input.</pre>';
    } else {
        $name = trim($name);
        $message = trim($message);

        $data = $db->prepare(
            'INSERT INTO guestbook (comment, name) VALUES (:message, :name)'
        );
        $data->bindValue(':message', $message, PDO::PARAM_STR);
        $data->bindValue(':name', $name, PDO::PARAM_STR);
        $data->execute();

        $html .= '<pre>Guestbook entry saved.</pre>';
    }
}

generateSessionToken();

?>
