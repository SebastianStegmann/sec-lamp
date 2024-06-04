<?php

header('Content-Type: application/json');
require_once __DIR__.'/../_.php';
try{
     
     //find orders where items = itemscreatedby userfk

     // TODO: validate "%{$_POST['query']}%  
     $db = _db();
     $q = $db->prepare('SELECT order_id, user_name, user_last_name, user_email, order_created_at, order_items, user_id
     FROM order_view_admin  
     WHERE (order_id LIKE :word COLLATE NOCASE 
     OR user_name LIKE :word COLLATE NOCASE
     OR user_last_name LIKE :word COLLATE NOCASE
     OR user_email LIKE :word COLLATE NOCASE
     OR order_items LIKE :word COLLATE NOCASE)
     AND user_id LIKE :user_id
     AND partner_id LIKE :partner_id
     ');
     
        // admin can search for all
        // if youre a customer, only be able to search for own orders
      if (_check_role('customer')) {
        $user_id = $_SESSION['user']['user_id'];
      } else {
        $user_id = "%";
      }
      $q->bindValue(':user_id',  $user_id);

      if (_check_role('partner')) {
        $partner_id = $_SESSION['user']['user_id'];
      } else {
        $partner_id = "%";
      }
      $q->bindValue(':partner_id',  $partner_id);

//       TODO ??
//       if youre a partner, search for all user_ids, but only for those ordering your products


//       if (_check_role('partner')) {
//         $partner_id = $_SESSION['user']['user_id'];
//       } else {
//         $partner_id = '*';
//       }



    
//       $q->bindValue(':user_id',  $partner_id);
      $q->bindValue(':word', "%{$_POST['query']}%");
      $q->execute();
      $customers = $q->fetchAll();

     echo json_encode($customers);

}catch(Exception $e){
        $status_code = !ctype_digit($e->getCode()) ? 500 : $e->getCode();
        $message = strlen($e->getMessage()) == 0 ? 'error - '.$e->getLine() : $e->getMessage();
        http_response_code($status_code);
        echo json_encode(['info'=>$message]);
}


?>