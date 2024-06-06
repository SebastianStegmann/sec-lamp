<?php
require_once __DIR__ . '/../_.php';
require_once __DIR__ . '/Faker/autoload.php';
$faker = Faker\Factory::create();

try {

  $db = _db();
  $q = $db->prepare('DROP TABLE IF EXISTS orders_items');
  $q->execute();
  $db = _db();
  $q = $db->prepare('DROP TABLE IF EXISTS orders');
  $q->execute();
  $db = _db();
  $q = $db->prepare('DROP TABLE IF EXISTS items');
  $q->execute();
  $db = _db();
  $q = $db->prepare('DROP TABLE IF EXISTS partners');
  $q->execute();
  $db = _db();
  $q = $db->prepare('DROP TABLE IF EXISTS employees');
  $q->execute();
  $db = _db();
  $q = $db->prepare('DROP TABLE IF EXISTS users');
  $q->execute();
  $db = _db();
  $q = $db->prepare('DROP TABLE IF EXISTS profile_pictures');
  $q->execute();
  $db = _db();
  $q = $db->prepare('DROP TABLE IF EXISTS roles');
  $q->execute();






  $q = $db->prepare('
    CREATE TABLE roles(
      role_id           BIGINT UNSIGNED AUTO_INCREMENT,
      role_name         VARCHAR(10),
      role_created_at   CHAR(10),
      role_updated_at   CHAR(10),
      PRIMARY KEY (role_id)
    )
  ');
  $q->execute();
  $created_at = time();
  // could / should the ids just be 1 2 3 and then refrence them instead
  $q = $db->prepare(" INSERT INTO roles VALUES 
                      (null, 'admin', $created_at, 0),
                      (null, 'partner', $created_at, 0),
                      (null, 'customer', $created_at, 0),
                      (null, 'employee', $created_at, 0)");
  $q->execute();

  echo "+ roles" . PHP_EOL;
} catch (Exception $e) {
  echo $e;
}
