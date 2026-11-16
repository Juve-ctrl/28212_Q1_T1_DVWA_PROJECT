<?php

if (isset($_GET['Submit'])) {

    checkToken(
        $_REQUEST['user_token'] ?? '',
        $_SESSION['session_token'] ?? '',
        'index.php'
    );

    $exists = false;
    $idInput = $_GET['id'] ?? null;

    if (
        is_string($idInput) &&
        $idInput !== '' &&
        ctype_digit($idInput) &&
        filter_var($idInput, FILTER_VALIDATE_INT) !== false
    ) {
        $id = (int) $idInput;

        switch ($_DVWA['SQLI_DB']) {

            case MYSQL:

                $statement = $db->prepare(
                    'SELECT 1 FROM users WHERE user_id = :id LIMIT 1'
                );

                $statement->bindValue(':id', $id, PDO::PARAM_INT);
                $statement->execute();

                $exists = ($statement->fetchColumn() !== false);

                break;

            case SQLITE:

                global $sqlite_db_connection;

                $statement = $sqlite_db_connection->prepare(
                    'SELECT 1 FROM users WHERE user_id = :id LIMIT 1'
                );

                $statement->bindValue(':id', $id, SQLITE3_INTEGER);
                $result = $statement->execute();

                if ($result !== false) {
                    $row = $result->fetchArray(SQLITE3_ASSOC);
                    $exists = ($row !== false);
                    $result->finalize();
                }

                break;
        }
    }

    if ($exists) {
        $html .= '<pre>User ID exists in the database.</pre>';
    } else {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        $html .= '<pre>User ID is MISSING from the database.</pre>';
    }
}

generateSessionToken();

?>
