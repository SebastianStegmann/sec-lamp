<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../_.php';
$db = null;

try {

    _validate_user_profile_picture();
    _validate_user_name();
    _validate_user_last_name();
    _validate_user_email();
    _validate_user_address();

    // $file_name = "1234iauhsdoa.png"; 

    $file_name = _generate_user_profile_picture();

    $db = _db();
    $db->beginTransaction();

    // Get the users current profile picture foreign key
    $user_pp_fk_q = $db->prepare('
        SELECT user_profile_picture_fk
        FROM users
        WHERE user_id = :user_id
    ');
    $user_pp_fk_q->bindValue(':user_id', $_POST['user_id']);
    $user_pp_fk_q->execute();
    $res = $user_pp_fk_q->fetch(PDO::FETCH_ASSOC);
    $current_profile_picture_id = (int)$res['user_profile_picture_fk'];

    // defaults to exact same value
    // value is modified if $file_name returns true
    $profile_picture_id = $current_profile_picture_id;
    
    // if no file name, nothing was uploaded
    if ( $file_name ) {

        // Get the users current profile picture path
        $pp_path_q = $db->prepare('
            SELECT profile_picture_path
            FROM profile_pictures
            WHERE profile_picture_id = :profile_picture_id
        ');
        $pp_path_q->bindValue( ':profile_picture_id', $current_profile_picture_id );
        $pp_path_q->execute();
        $res = $pp_path_q->fetch(PDO::FETCH_ASSOC);
        $current_profile_picture_path = $res['profile_picture_path'];


        // Insert recently uploaded image to profile_pictures table
        $insert_q = $db->prepare('
            INSERT INTO profile_pictures
            VALUES (null, :profile_picture_path)
        ');
        $insert_q->bindValue( ':profile_picture_path', $file_name );
        $insert_q->execute();
        $profile_picture_id = $db->lastInsertId();

        // As constraint is preventing us from deleting row in profile_pictures table,
        // we have to update user_profile_picture_fk to new value first
        $update_q = $db->prepare('
            UPDATE users 
            SET user_profile_picture_fk = :user_profile_picture_fk
            WHERE user_id = :user_id
        ');
        $update_q->bindValue( ':user_profile_picture_fk', $profile_picture_id );
        $update_q->bindValue( ':user_id', $_POST['user_id'] );
        $update_q->execute();

        // Don't delete default
        if ( $current_profile_picture_id !== 1 ) {
            // Remove current image from uploads folder
            _remove_user_profile_picture( $current_profile_picture_path );

            // Remove current image from profile_pictures table
            $delete_q = $db->prepare('
                DELETE FROM profile_pictures
                WHERE profile_picture_id = :profile_picture_id'
            );
            $delete_q->bindValue( ':profile_picture_id', $current_profile_picture_id );
            $delete_q->execute();
        }
    }

    // TODO: validate user name
    // write in SQL in phpmyadmin
    // NEVER put variable inside data
    $q = $db->prepare('
        UPDATE users 
        SET user_name = :user_name,
            user_last_name = :user_last_name,
            user_address = :user_address,
            user_updated_at = :user_updated_at
        WHERE user_id = :user_id
    ');
    // press go button
    $q->bindValue(':user_name', $_POST['user_name']);
    $q->bindValue(':user_last_name', $_POST['user_last_name']);
    $q->bindValue(':user_address', $_POST['user_address']);
    $q->bindValue(':user_updated_at', time());
    $q->bindValue(':user_id', $_POST['user_id']);
    // $q->bindValue(':user_id', $_SESSION['user']['user_id']);

    $q->execute();

    $counter = $q->rowCount();

    if ($_SESSION['user']['user_role_fk'] == 1) {
        echo json_encode(["redirect" => "/profile/" . $_POST['user_id']]);
    } else {
        echo json_encode(["redirect" => "/profile"]);
    }

    $db->commit();

    exit();
} catch (Exception $e) {
    if ( $db !== null && $db->inTransaction() ) {
        $db->rollBack();
    }
    $status_code = !ctype_digit($e->getCode()) ? 500 : $e->getCode();
    $message = strlen($e->getMessage()) == 0 ? 'error - ' . $e->getLine() : $e->getMessage();
    http_response_code($status_code);
    echo json_encode(['info' => $message]);
}
