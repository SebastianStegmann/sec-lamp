<?php
$page_title = 'Admin';
require_once __DIR__.'/../_.php';
if( ! _check_role('1')){
  header('Location: /login'); 
  exit();
}

require_once __DIR__.'/_header.php';

?>
<div class="">
  
  <h1 class="text-2xl">Stats</h1>
  <div id="stats_orders" class="">
    <h2 class="text-xl">Order stats</h2>
    <p>Total orders</p>
    <p>Open orders</p>
    <p>Closed orders</p>
    <p>Average price pr order</p>
    <p>Average price pr order this month</p>
    <p>Average order pr month</p>
    <p>Orders current month</p>
    <p>Average items pr order</p>
    <p>How much money sold</p>
  </div>
  <div id="stats_users">
    <h2 class="text-xl">User stats</h2>
    <p>Total users</p>
    <p>Users gained month</p>
  </div>
  <div id="stats_customers">
    <h2 class="text-xl">Customer stats</h2>
    <p>Total amount of customers</p>
    <p>Customers gained this month</p>
  </div>
  <div id="stats_partners">
    <h2 class="text-xl">Partner stats</h2>
    <p>Total amount of partners</p>
    <p>Partners gained this month</p>
  </div>

  <div id="stats_employees">
    <h2 class="text-xl">Employee stats</h2>
    <p>Total amount of employees</p>
    <p>Employees gained this month</p>
  </div>
</div>






<!-- stats for the business -->
<!-- Amount of users, customers, partners, employees
amount of orders, open orders, closed orders
amount of items, 
how much sold this day, week, month, year
customer/employee/partner gained over day week month year

-->


<?php
require_once __DIR__.'/_footer.php';
?>