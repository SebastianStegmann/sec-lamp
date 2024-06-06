<?php
require_once __DIR__.'/../_.php';
require_once __DIR__ . '/Faker/autoload.php';
$faker = Faker\Factory::create();

try{

  $db = _db();
  $q = $db->prepare('DROP TABLE IF EXISTS partners_comments');
  $q->execute();

  $q = $db->prepare('
    CREATE TABLE partners_comments (
        partner_id_fk         bigint(20) UNSIGNED NOT NULL,
        user_id_fk            bigint(20) UNSIGNED NOT NULL,
        comment               varchar(1000) NOT NULL,
        comment_created_at    char(10) NOT NULL,
        FOREIGN KEY (user_id_fk) REFERENCES users(user_id),
        FOREIGN KEY (partner_id_fk) REFERENCES partners(partner_id)
    )
  ');
  $q->execute();

  echo "+ partners_comments".PHP_EOL;
}catch(Exception $e){
  echo $e;
}
