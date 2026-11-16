<?php

if (isset($_GET['Submit'])) {

    checkToken(
        $_REQUEST['user_token'] ?? '',
        $_SESSION['session_token'] ?? '',
        'index.php'
    );

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
                    'SELECT first_name, last_name
                     FROM users
                     WHERE user_id = :id
                     LIMIT 1'
                );

                $statement->bindValue(':id', $id, PDO::PARAM_INT);
                $statement->execute();

                $row = $statement->fetch(PDO::FETCH_ASSOC);

                if ($row !== false) {
                    $first = htmlspecialchars(
                        (string) $row['first_name'],
                        ENT_QUOTES | ENT_SUBSTITUTE,
                        'UTF-8'
                    );

                    $last = htmlspecialchars(
                        (string) $row['last_name'],
                        ENT_QUOTES | ENT_SUBSTITUTE,
                        'UTF-8'
                    );

                    $html .= "<pre>ID: {$id}<br />First name: {$first}<br />Surname: {$last}</pre>";
                }

                break;

            case SQLITE:

                global $sqlite_db_connection;

                $statement = $sqlite_db_connection->prepare(
                    'SELECT first_name, last_name
                     FROM users
                     WHERE user_id = :id
                     LIMIT 1'
                );

                $statement->bindValue(':id', $id, SQLITE3_INTEGER);
                $result = $statement->execute();

                if ($result !== false) {
                    $row = $result->fetchArray(SQLITE3_ASSOC);

                    if ($row !== false) {
                        $first = htmlspecialchars(
                            (string) $row['first_name'],
                            ENT_QUOTES | ENT_SUBSTITUTE,
                            'UTF-8'
                        );

                        $last = htmlspecialchars(
                            (string) $row['last_name'],
                            ENT_QUOTES | ENT_SUBSTITUTE,
                            'UTF-8'
                        );

                        $html .= "<pre>ID: {$id}<br />First name: {$first}<br />Surname: {$last}</pre>";
                    }

                    $result->finalize();
                }

                break;
        }
    } else {
        $html .= '<pre>Invalid user ID. Enter a valid positive integer.</pre>';
    }
}

generateSessionToken();

?>
