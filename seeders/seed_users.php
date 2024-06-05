<?php
require_once __DIR__.'/../_.php';
require_once __DIR__ . '/Faker/autoload.php';
$faker = Faker\Factory::create();

try{

  $db = _db();
  $q = $db->prepare('DROP TABLE IF EXISTS users');
  $q->execute();

  // Get roles to assign to users

  // should this be the role id??
  $q = $db->prepare('SELECT role_name FROM roles');
  $q->execute();
  $roles = $q->fetchAll(PDO::FETCH_COLUMN); // ["admin","partner","user","employee"]



  $q = $db->prepare('
    CREATE TABLE users(
      user_id           BIGINT UNSIGNED AUTO_INCREMENT,
      user_name         VARCHAR(20),
      user_last_name    VARCHAR(20),
      user_email        VARCHAR(50) UNIQUE,
      user_password     VARCHAR(255),
      user_address      VARCHAR(255),
      user_role_fk      BIGINT UNSIGNED,
      user_tag_color    VARCHAR(9),
      user_profile_picture_fk BIGINT UNSIGNED,
      user_created_at   CHAR(10),
      user_is_blocked   BOOLEAN,
      user_updated_at   CHAR(10),
      user_deleted_at   CHAR(10),
      PRIMARY KEY (user_id),
      FOREIGN KEY (user_role_fk) REFERENCES roles(role_id),
      FOREIGN KEY (user_profile_picture_fk) REFERENCES profile_pictures(profile_picture_id)
    )
  ');
  $q->execute();

  $values = '';
  // Admin roles

  $admin_password = password_hash('password', PASSWORD_DEFAULT);
  $admin_created_at = time();
  $admin_is_blocked= 0;
  $admin_updated_at = 0;
  $admin_deleted_at = 0;  
  $values .= "(null, 'Admin', 'Admin', 'admin@company.com', 
  '$admin_password', 'Admin address', 1, '#0ea5e9', 1, $admin_created_at, $admin_is_blocked, $admin_updated_at, $admin_deleted_at),";


  $user_password = password_hash('password', PASSWORD_DEFAULT); // too long time in loop
  for($i = 0; $i < 100; $i++){
    // $user_id = bin2hex(random_bytes(16));
    $user_name = str_replace("'", "''", $faker->firstName);
    $user_last_name = str_replace("'", "''", $faker->lastName);
    $user_email = $faker->unique->email;
    $user_address = str_replace("'", "''", $faker->address);
    // $user_role_fk = $roles[array_rand($roles)];
    $user_role_fk = rand(2,4);
    $user_tag_color = $faker->hexcolor;
    $user_profile_picture_fk = 1;
    $user_created_at = time();
    $user_is_blocked = 0;
    $user_updated_at = 0;
    $user_deleted_at = 0;
    $values .= "(null, '$user_name', '$user_last_name', '$user_email', '$user_password', 
    '$user_address', '$user_role_fk', '$user_tag_color', $user_profile_picture_fk, $user_created_at, $user_is_blocked,  $user_updated_at, $user_deleted_at),";
  }
  $values = rtrim($values, ',');  
  $q = $db->prepare("INSERT INTO users VALUES $values");
  $q->execute();

  echo "+ users".PHP_EOL;
}catch(Exception $e){
  echo $e;
}









