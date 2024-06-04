<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../_.php';


try {


    _validate_user_name();
    _validate_user_last_name();
    _validate_user_email();
    _validate_user_address();

    // TODO: validate user name
    $db = _db();
    // write in SQL in phpmyadmin
    // NEVER put variable inside data
    $q = $db->prepare('UPDATE users 
                       SET user_name = :user_name,
                           user_last_name = :user_last_name,
                           user_address = :user_address,
                           user_updated_at = :user_updated_at
                       WHERE user_id = :user_id
                       ');
    // press go button
    $q->bindValue(':user_name', $_POST['user_name']);
    $q->bindValue(':user_last_name', $_POST['user_last_name']);
    $q->bindValue(':user_address', $_POST['user_address']);
    $q->bindValue(':user_updated_at', time());
    $q->bindValue(':user_id', $_POST['user_id']);
    // $q->bindValue(':user_id', $_SESSION['user']['user_id']);

    $q->execute();

    $counter = $q->rowCount();

    if ($_SESSION['user']['user_role_fk'] == 1) {
        echo json_encode(["redirect" => "/profile/" . $_POST['user_id']]);
    } else {
        echo json_encode(["redirect" => "/profile"]);
    }

    exit();
} catch (Exception $e) {
    $status_code = !ctype_digit($e->getCode()) ? 500 : $e->getCode();
    $message = strlen($e->getMessage()) == 0 ? 'error - ' . $e->getLine() : $e->getMessage();
    http_response_code($status_code);
    echo json_encode(['info' => $message]);
}
