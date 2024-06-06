<?php

require_once __DIR__ . '/../_.php';
require_once __DIR__ . '/Faker/autoload.php';
$faker = Faker\Factory::create();

try {
    $db = _db();
    $q = $db->prepare('DROP TABLE IF EXISTS partners');
    $q->execute();

    // Get users who are employees to assign a salary to them
    $user_role_fk = 2;
    $q = $db->prepare("SELECT user_id FROM users WHERE user_role_fk = :user_role_fk");
    $q->bindParam(':user_role_fk', $user_role_fk, PDO::PARAM_STR);
    $q->execute();
    $partners_ids = $q->fetchAll(PDO::FETCH_COLUMN);

    $q = $db->prepare('
        CREATE TABLE partners(
          partner_id                       BIGINT UNSIGNED,
          partner_hidden                   BOOLEAN,
          PRIMARY KEY (partner_id),
          FOREIGN KEY (partner_id) REFERENCES users(user_id)
        )
    ');
    $q->execute();

    $partner_hidden = 0;

    $values = '';
    foreach ($partners_ids as $partner_id) {
        $values .= "($partner_id, $partner_hidden),";
    }
    $values = rtrim($values, ',');
    $q = $db->prepare("INSERT INTO partners VALUES $values");
    $q->execute();

    echo "+ partners" . PHP_EOL;
} catch (Exception $e) {
    echo $e;
}
