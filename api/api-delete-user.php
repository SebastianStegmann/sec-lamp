<?php
header('Content-Type: application/json');
require_once __DIR__.'/../_.php';
try{
    // if user role not admin, ask for password
    // if admin only ask for confirmation 'are you sure'
    


    // check if already deleted ?? if user_deleted_at != 0
    // can i use post for all, or do i use session for customer and partner, and
    // post for admin
    // should deleted/blocked users be able to login
    // SOFT DELETE

    $db = _db();

    $q = $db->prepare('SELECT user_id, user_deleted_at, user_password
        FROM users
        WHERE user_id = :user_id
    ');

    if ($_SESSION['user']['user_role_name'] == 'admin') {
        $q->bindValue(':user_id', $_POST['user_id']);
    } else {
        $q->bindValue(':user_id', $_SESSION['user']['user_id']);
    }

    $q->execute();
    $user = $q->fetch();


    if ($_SESSION['user']['user_role_name'] != 'admin') {
        if( ! password_verify($_POST['current_user_password'], $user['user_password']) ){
            echo 'Try again';
            return;
        }
    }

    $info = '';
    // Undo soft delete
    if ($user['user_deleted_at'] != 0) {
        
        $info .= 'user already deleted';
       
        if( ! _check_role('admin')) {
            $info .= 'No admin priveleges';
            echo json_encode(['info' => $info]);
            exit();
        }
    
        $q = $db->prepare('UPDATE users
                           SET user_deleted_at = 0
                           WHERE user_id = :user_id
                            ');
    
        $q->bindValue(':user_id', $user['user_id']);   
    
        $q->execute();

        $info .= ' --   user undeleted';

        echo json_encode(['info' => $info]);
    


        exit();
    }

    $q = $db->prepare('UPDATE users
                       SET user_deleted_at = :user_deleted_at
                       WHERE user_id = :user_id
                        ');

   
    // $q = $db->prepare('DELETE FROM users
    // WHERE user_id = :user_id
    // ');
    
    $q->bindValue(':user_id', $user['user_id']);
    $q->bindValue(':user_deleted_at', time());

    $q->execute();

    $info .= $user['user_id'] . ' was deleted at ' . time();

    echo json_encode(['info' => $info]);

    if (! _check_role('admin')) {
        header('Location: /logout');
    } 

    exit();
    
    

    // if already deleted aka == only admins can undelete
    // should be fine, since users cant log in when deleted




}catch(Exception $e){
        $status_code = !ctype_digit($e->getCode()) ? 500 : $e->getCode();
        $message = strlen($e->getMessage()) == 0 ? 'error - '.$e->getLine() : $e->getMessage();
        http_response_code($status_code);
        echo json_encode(['info'=>$message]);
}