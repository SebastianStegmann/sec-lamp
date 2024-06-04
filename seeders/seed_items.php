<?php
require_once __DIR__.'/../_.php';
require_once __DIR__ . '/Faker/autoload.php';
$faker = Faker\Factory::create();

try{

  $db = _db();
  $q = $db->prepare('DROP TABLE IF EXISTS items');
  $q->execute();

  // Get users whom are partners to assign to items
  $user_role_name = 'partner';
  $q = $db->prepare("SELECT user_id FROM users WHERE user_role_fk = 2");
  $q->execute();
  $users_ids = $q->fetchAll(PDO::FETCH_COLUMN); // ["admin","partner","user","employee"]
  // echo json_encode($users_ids); exit();
  $q = $db->prepare('
    CREATE TABLE items(
      item_id                   BIGINT UNSIGNED AUTO_INCREMENT,
      item_name                 VARCHAR(20),
      item_price                DECIMAL(10,2),
      item_created_at           CHAR(10),
      item_updated_at           CHAR(10),
      item_deleted_at           CHAR(10),
      item_created_by_user_fk   BIGINT UNSIGNED,
      PRIMARY KEY (item_id),
      FOREIGN KEY (item_created_by_user_fk) REFERENCES users(user_id)
    )
  ');
  $q->execute();
  $values = '';
  for($i = 0; $i < 100; $i++){

    $item_name = str_replace("'", "''", $faker->unique->word);
    $item_price = rand(2, 150);
    $item_created_at = time();
    $item_updated_at = 0;
    $item_deleted_at = 0;
    $item_created_by_user_fk = $users_ids[array_rand($users_ids)];
    $values .= "(null, '$item_name', $item_price, $item_created_at, $item_updated_at, $item_deleted_at, '$item_created_by_user_fk'),";
  }
  $values = rtrim($values, ',');  
  $q = $db->prepare("INSERT INTO items VALUES $values");
  $q->execute();

  echo "+ items".PHP_EOL;
}catch(Exception $e){
  echo $e;
}









