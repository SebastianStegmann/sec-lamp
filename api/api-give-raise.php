<?php 

header('Content-Type: application/json');
require_once __DIR__.'/../_.php';
try{
    $amount = $_GET['amount'];
    $user_id = $_GET['user_id'];
    $current_salary = $_GET['curr_sal'];

    $new_salary = $amount + $current_salary;

    $db = _db();

    $q = $db->prepare(' UPDATE employees
                        SET employee_hourly_pay = :employee_salary
                        WHERE employee_id = :user_id');


    $q->bindParam(':employee_salary', $new_salary, PDO::PARAM_INT);
    $q->bindParam(':user_id', $user_id);

    $q->execute();


    echo json_encode(['info' => 'update succesfull']);    

}catch(Exception $e){
        $status_code = !ctype_digit($e->getCode()) ? 500 : $e->getCode();
        $message = strlen($e->getMessage()) == 0 ? 'error - '.$e->getLine() : $e->getMessage();
        http_response_code($status_code);
        echo json_encode(['info'=>$message]);
}