<?php
require_once __DIR__.'/_header.php';
$tag = 'my_orders';
$frm_search_url = "api-search-orders.php";
$db = _db();

require_once __DIR__ . '/_pagination.php';

$q = $db->prepare("SELECT * FROM order_view_admin
                  WHERE user_id = :user_id
                  LIMIT :limit OFFSET :offset
                   ");

// part of pagination
$q->bindParam(':user_id', $_SESSION['user']['user_id']);
$q->bindParam(':limit', $result_pr_page, PDO::PARAM_INT);
$q->bindParam(':offset', $offset, PDO::PARAM_INT);


$q->execute();
$orders = $q->fetchAll();    

// echo json_encode($orders); exit();

?>


<div class="grid grid-cols-[max-content,1fr] md:grid-cols-[max-content,15fr,22fr] py-4 ">
    <h1 class="text-black text-xl">
      My orders
    </h1>
  
    <!-- only show search if there are orders -->
    <?php if( ! $orders ): ?>
    <div class="col-span-2 w-full md:col-span-1 md:col-start-3 md:row-start-1">
      <?php include_once __DIR__ . '/_search_for.php' ?>
    </div>
    <?php endif ?>
  </div>

  <?php if( ! $orders ): ?>
    <h1>You have no orders in the system</h1>
  <?php endif ?>

  <?php foreach($orders as $order): ?>
    <div class=" gap-0 border-b border-b-slate-200 py-2 ">
      <p class=" "><?= $order['user_id'] ?></p>
      
      <p class=" hidden"><?= $order['user_id'] ?></p>
    </div>
  <?php endforeach ?>

      <?= $pagination_buttons ?>


<?php
require_once __DIR__.'/_footer.php';
?>
