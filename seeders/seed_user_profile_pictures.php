<?php
require_once __DIR__.'/../_.php';
require_once __DIR__ . '/Faker/autoload.php';
$faker = Faker\Factory::create();

try{

    $db = _db();
    $q = $db->prepare('DROP TABLE IF EXISTS users_profile_pictures');
    $q->execute();

    $q = $db->prepare('
        CREATE TABLE `profile_pictures` (
            `profile_picture_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `profile_picture_path` varchar(255) NOT NULL,
            PRIMARY KEY (`profile_picture_id`),
            UNIQUE KEY `profile_picture_path` (`profile_picture_path`)
        )
    ');
    $q->execute();

    $q = $db->prepare('
        INSERT INTO profile_pictures 
        VALUES (
            null,
            :profile_picture_path
        )'
    );

    $q->bindValue(':profile_picture_path', 'default.png');
    $q->execute();

  echo "+ profile_pictures" . PHP_EOL;
}
catch(Exception $e){
    echo $e;
}