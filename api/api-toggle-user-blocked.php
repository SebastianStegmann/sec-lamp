<?php
header('Content-Type: application/json');
require_once __DIR__.'/../_.php';
try{

    
// TODO: make sure this is the admin user

// this isnt right, it should work some other way

if(!_is_admin()){ echo 'Not admin'; exit(); };


// TODO: check in the session the users role
$user_id = $_POST['user_id'];
$user_is_blocked = $_POST['user_is_blocked'];

$db = _db();

$q = $db->prepare("UPDATE users 
SET user_is_blocked = CASE 
    WHEN user_is_blocked = 0 THEN 1 
    ELSE 0 
END 
WHERE user_id = :user_id
                ");

$q->bindValue(':user_id', $user_id);

$q->execute();

echo json_encode(['info' => 'user updated']);

echo $user_id;
echo $user_is_blocked;

    

}catch(Exception $e){
        $status_code = !ctype_digit($e->getCode()) ? 500 : $e->getCode();
        $message = strlen($e->getMessage()) == 0 ? 'error - '.$e->getLine() : $e->getMessage();
        http_response_code($status_code);
        echo json_encode(['info'=>$message]);
}