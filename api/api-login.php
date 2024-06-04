<?php 
header('Content-Type: application/json');
require_once __DIR__.'/../_.php';
try{
  $db = _db();
  $q = $db->prepare('SELECT * FROM users WHERE user_email = :user_email');

  $_POST['user_email'] = trim($_POST['user_email']);
  $q->bindValue(':user_email', $_POST['user_email']);
  $q->execute();
  $user = $q->fetch();


  // does the user exist
  if( ! $user ){
    throw new Exception('invalid credentials', 400);
    return;
  }

// are they blocked or deleted
  if ( _is_allowed($user['user_is_blocked'], $user['user_deleted_at']) == false ){
    // dont tell them they have been blocked or deleted??
    // throw new Exception('You have been blocked or (soft)deleted', 400);
    throw new Exception('user blocked', 400);
    return;
    }

  // did they pass the right password
  if( ! password_verify($_POST['user_password'], $user['user_password']) ){
    throw new Exception('invalid credentials', 400);
  }

  // session_start();
  unset($user['user_password']);
  // echo json_encode($user);


  // echo json_encode($_SESSION['user']);
  $_SESSION['user'] = $user;
  if ($user['user_role_fk'] === 1) {
    echo json_encode(['redirect' => '/admin']);
} else {
    echo json_encode(['redirect' => '/']);
}

  

}catch(Exception $e){
        $status_code = !ctype_digit($e->getCode()) ? 500 : $e->getCode();
        $message = strlen($e->getMessage()) == 0 ? 'error - '.$e->getLine() : $e->getMessage();
        http_response_code($status_code);
        echo json_encode(['info'=>$message]);
}