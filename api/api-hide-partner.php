<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../_.php';
try {

    $hide_bool = $_POST['hide_bool'];

    $db = _db();

    $q = $db->prepare('UPDATE partners
SET partner_hidden = :value
WHERE partner_id = :partner_id;');

    $q->bindValue(':value', $hide_bool == 0 ? 1 : 0);
    $q->bindValue(':partner_id', $_SESSION['user']['user_id'] == 1 ? $_POST['user_id'] : $_SESSION['user']['user_id']);
    $q->execute();


    echo json_encode(['info' => 'partner hidden status updated.']);
    header('Location: /profile/' . ($_SESSION['user']['user_id'] == 1 ? $_POST['user_id'] : $_SESSION['user']['user_id']));
    exit();
} catch (Exception $e) {
    $status_code = !ctype_digit($e->getCode()) ? 500 : $e->getCode();
    $message = strlen($e->getMessage()) == 0 ? 'error - ' . $e->getLine() : $e->getMessage();
    http_response_code($status_code);
    echo json_encode(['info' => $message]);
}
