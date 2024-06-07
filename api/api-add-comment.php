<?php 
header('Content-Type: application/json');
require_once __DIR__.'/../_.php';
try {
    // http referer from where the api was initiated
    $referer = $_SERVER['HTTP_REFERER'];
    $user_id = $_SESSION['user']['user_id'];
    $comment = _validate_textcomment();

    if ( !$referer ) {
        throw new Exception('Could not retrieve URL from where comment was added.', 500);
    }

    if ( !$user_id ) {
        throw new Exception('User could not be found.', 400);
    }

    $parsed_url = parse_url( $referer, PHP_URL_PATH );
    $segments = array_filter( explode('/', $parsed_url ) );
    $partner_id = end( $segments );

    if ( !is_numeric( $partner_id ) ) {
        throw new Exception('Could not get restaurant.', 400);
    }

    // Get partner
    $db = _db();
    $q = $db->prepare('
        SELECT * FROM partners
        WHERE partner_id = :partner_id
    ');

    $q->bindValue( ':partner_id', $partner_id );
    $q->execute();
    $partner = $q->fetch();

    if ( ! $partner ) {
        throw new Exception('Could not find partner.', 400);
    }

    // Get user and confirm it's a valid user
    $db = _db();
    $q = $db->prepare('
        SELECT * FROM users
        WHERE user_id = :user_id
    ');

    $q->bindValue( ':user_id', $user_id );
    $q->execute();
    $user = $q->fetch();

    // does the user exist
    if( ! $user ) {
        throw new Exception('User could not be found.', 400);
    }

    // are they blocked or deleted
    if ( _is_allowed($user['user_is_blocked'], $user['user_deleted_at']) == false ){
        throw new Exception('Error adding comment.', 400);
    }

    if ( $partner['partner_id'] == $user_id ) {
        throw new Exception('Cannot add comment to own restaurant.', 400);
    }

    if ( $partner['partner_hidden'] == 1 && $user['user_role_fk'] > 1 ) {
        throw new Exception('Error adding comment.', 400);
    }

    $db = _db();
    $q = $db->prepare('
        INSERT INTO partners_comments
        VALUES(
            :partner_id_fk,
            :user_id_fk,
            :comment,
            :comment_created_at
        )
    ');
    $q->bindValue( ':partner_id_fk', $partner['partner_id'] );
    $q->bindValue( ':user_id_fk', $user_id );
    $q->bindValue( ':comment', $comment );
    $q->bindValue( ':comment_created_at', time() );
    $q->execute();


    // // session_start();
    // // echo json_encode($user);

    // redirect to current partner ID
    echo json_encode(['redirect' => '/restaurant/' . $partner['partner_id']]);

} catch(Exception $e){
    $status_code = !ctype_digit($e->getCode()) ? 500 : $e->getCode();
    $message = strlen($e->getMessage()) == 0 ? 'error - '.$e->getLine() : $e->getMessage();
    http_response_code($status_code);
    echo json_encode(['info'=>$message]);
}