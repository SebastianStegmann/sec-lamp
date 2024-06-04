<?php 
header('Content-Type: application/json');
require_once __DIR__.'/../_.php';
try{

    $add_to_cart = $_GET['item_id'];
    // $_SESSION['cart'] = [];

    if(array_key_exists($add_to_cart, $_SESSION['cart']) == true) {
        $_SESSION['cart'][$add_to_cart]++; 
    } else {
        $_SESSION['cart'][$add_to_cart] = 1;
    }


    echo json_encode(['info' => $_SESSION['cart']]);    


}catch(Exception $e){
        $status_code = !ctype_digit($e->getCode()) ? 500 : $e->getCode();
        $message = strlen($e->getMessage()) == 0 ? 'error - '.$e->getLine() : $e->getMessage();
        http_response_code($status_code);
        echo json_encode(['info'=>$message]);
}