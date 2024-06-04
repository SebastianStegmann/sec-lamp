<?php 
header('Content-Type: application/json');
require_once __DIR__.'/../_.php';
try{

    _validate_user_password();
    _validate_user_confirm_password();

    // if post user pass = current user pass
    // update where userid :userid
    $db = _db();

    $q = $db->prepare('SELECT user_password 
                        FROM USERS 
                        WHERE user_id = :user_id');

    // $q->bindValue(':user_id', $_SESSION['user']['user_id']);
    $q->bindValue(':user_id', $_POST['user_id']);   
    
    
    $q->execute();

    $current_user_password = $q->fetch();
    echo json_encode($current_user_password);
    echo json_encode($_SESSION['user']['user_id']);
    echo json_encode($_POST['current_user_password']); 
    // exit();
    // hash somethign ?? maybe hash the input and match?
    $error = 'Wrong password';
    if( ! password_verify($_POST['current_user_password'], $current_user_password['user_password']) ){
        echo 'Try again';
        return;
    }
    echo json_encode(['info' => 'password correct']);
    unset($current_user_password['user_password']);
    echo json_encode($current_user_password);

    $q = $db->prepare('UPDATE users 
                       SET user_password = :user_password
                       WHERE user_id = :user_id
                       ');

    // TODO ?? Do i set the password to the real password, or do i hash it?
    // password_hash(  $_POST['user_password'], PASSWORD_DEFAULT)

    $q->bindValue(':user_id', $_SESSION['user']['user_id']);
    $q->bindValue(':user_password', password_hash($_POST['user_password'], PASSWORD_DEFAULT));


    $q->execute();
    $counter = $q->rowCount();
    if ($counter != 1 ){
        // throw new Exception('ups...',500);
        echo 'something row count api user password';
    }
    
    // $q = $db->prepare('UPDATE users 
    // SET user_name = :user_name,
    //     user_last_name = :user_last_name,
    //     user_address = :user_address 
    // WHERE user_id = :user_id
    // ');

    // refresh page
    if (isset($_GET['user'])) {
        $user = $_GET['user'];
        header("Location: /profile?user=$user");
        exit();
    }
    header("Location: /profile");
    exit();

  

}catch(Exception $e){
        $status_code = !ctype_digit($e->getCode()) ? 500 : $e->getCode();
        $message = strlen($e->getMessage()) == 0 ? 'error - '.$e->getLine() : $e->getMessage();
        http_response_code($status_code);
        echo json_encode(['info'=>$message]);
}
