<?php

require_once __DIR__.'/../_.php';
require_once __DIR__ . '/Faker/autoload.php';
$faker = Faker\Factory::create();

try {
    $db = _db();
    $q = $db->prepare('DROP TABLE IF EXISTS employees');
    $q->execute();

    // Get users who are employees to assign a salary to them
    $user_role_fk = 4;
    $q = $db->prepare("SELECT user_id FROM users WHERE user_role_fk = :user_role_fk");
    $q->bindParam(':user_role_fk', $user_role_fk, PDO::PARAM_STR);
    $q->execute();
    $employees_ids = $q->fetchAll(PDO::FETCH_COLUMN);

    $q = $db->prepare('
        CREATE TABLE employees(
          employee_id                       BIGINT UNSIGNED,
          employee_hourly_pay               INT,
          employee_salary                   INT,
          employee_created_at               CHAR(10),
          employee_updated_at               CHAR(10),
          employee_deleted_at               CHAR(10),
          PRIMARY KEY (employee_id),
          FOREIGN KEY (employee_id) REFERENCES users(user_id)
        )
    ');
    $q->execute();

    $employee_updated_at = 0;
    $employee_deleted_at = 0;

    $values = '';
    foreach ($employees_ids as $employee_id) {
        $employee_salary = rand(3500000, 25000000);
        $employee_hourly_pay = $employee_salary / 2000;
        $employee_created_at = rand(time() - 1602343484, time());
        $values .= "($employee_id, $employee_hourly_pay, $employee_salary, $employee_created_at, 
        $employee_updated_at, $employee_deleted_at),";
    }
    $values = rtrim($values, ',');
    $q = $db->prepare("INSERT INTO employees VALUES $values");
    $q->execute();

    echo "+ employees" . PHP_EOL;
} catch (Exception $e) {
    echo $e;
}








