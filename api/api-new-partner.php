<?php
header('Content-Type: application/json');
require_once __DIR__.'/../_.php';
try{
//  ?? check if email exists already, CASE insensitive - server already has UNIQUE constraint
  _validate_user_name();
  _validate_user_last_name();
  _validate_user_email();
  _validate_user_address();
  _validate_user_password();
  _validate_user_confirm_password();

  $db = _db();

  $q = $db->prepare('
    INSERT INTO users 
    VALUES (
    :user_id,
    :user_name,
    :user_last_name,
    :user_email,
    :user_password,
    :user_address,
    :user_role_name,
    :user_tag_color,
    :user_created_at,
    :user_is_blocked,
    :user_updated_at,
    :user_deleted_at
  )');

  // $user_id = bin2hex(random_bytes(5));

  function rand_color() {
    return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
}


  $q->bindValue(':user_id', bin2hex(random_bytes(16)));
  $q->bindValue(':user_name', $_POST['user_name']);
  $q->bindValue(':user_last_name', $_POST['user_last_name']);
  $q->bindValue(':user_email', $_POST['user_email']);
  $q->bindValue(':user_password', password_hash(  $_POST['user_password'], PASSWORD_DEFAULT));
  $q->bindValue(':user_address', $_POST['user_address']);
  $q->bindValue(':user_role_name', 'partner');
  $q->bindValue(':user_tag_color', rand_color()); 
  $q->bindValue(':user_created_at', time());
  $q->bindValue(':user_is_blocked', 0);
  $q->bindValue(':user_updated_at', 0);
  $q->bindValue(':user_deleted_at', 0);

  $q->execute();
  $counter = $q->rowCount();
  if ($counter != 1 ){
    throw new Exception('ups...',500);
  }



  echo json_encode(['user_id' => $db->lastInsertId()]);
 
  

}catch(Exception $e){
    $status_code = !ctype_digit($e->getCode()) ? 500 : $e->getCode();
    $message = strlen($e->getMessage()) == 0 ? 'error - '.$e->getLine() : $e->getMessage();
    http_response_code($status_code);
    echo json_encode(['info'=>$message]);
}



