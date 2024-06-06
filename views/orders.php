<?php
$frm_search_url = 'api-search-orders.php';
$tag = 'orders';
$page_title = 'Orders';
require_once __DIR__ . '/_header.php';
// if(!_is_admin()){ header('Location: /login'); exit(); };
$db = _db();

require_once __DIR__ . '/_pagination.php';

$q = $db->prepare("SELECT * FROM order_view_admin
                   WHERE user_id LIKE :user_id
                   AND partner_id LIKE :partner_id
                   LIMIT :limit OFFSET :offset
                   ");


// if customer only show own orders
if (_check_role('3')) {
  $user_id = $_SESSION['user']['user_id'];
} else {
  $user_id = "%";
}
$q->bindValue(':user_id',  $user_id);

// If partner only show orders of own products
if (_check_role('2')) {
  $partner_id = $_SESSION['user']['user_id'];
} else {
  $partner_id = "%";
}
$q->bindValue(':partner_id',  $partner_id);

// part of pagination
$q->bindParam(':limit', $result_pr_page, PDO::PARAM_INT);
$q->bindParam(':offset', $offset, PDO::PARAM_INT);

$q->execute();
$orders = $q->fetchAll();
?>

<div class="grid grid-cols-[max-content,1fr] md:grid-cols-[max-content,15fr,22fr] py-4 ">
  <!-- title -->
  <h1 class="text-xl">
    Orders
  </h1>

  <!-- search -->
  <div class="col-span-2 w-full md:col-span-1 md:col-start-3 md:row-start-1">
    <?php include_once __DIR__ . '/_search_for.php' ?>
  </div>
</div>

<?php if (!$orders) : ?>
  <h1>You have no orders in the system</h1>
<?php endif ?>

<?php foreach ($orders as $order) : ?>
  <div class="grid grid-cols-2 gap-0 border-b border-b-slate-200 py-2 ">
    <p class=" hidden"><?= $order['user_id'] ?></p>
    <p class="col-start-1 text-3xl font-medium"><?php out($order['user_name'] . " " . $order['user_last_name']); ?></p>
    <p class=" font-extralight text-sm ">Order id: <?php out($order['order_id']) ?></p>


    <p class="col-start-1 "><?php out($order['user_email']) ?></p>
    <p class="col-start-1"><?php out($order['user_address']) ?></p>

    <?php if ($order['order_delivered_at'] == 0) : ?>
      <p>Pending</p>
    <?php elseif (($order['order_delivered_at'] == 1)) : ?>
      <p>On route</p>
    <?php elseif (($order['order_delivered_at'] > 1)) : ?>
      <p>Delivered: <?php out(date('D M Y', $order['order_delivered_at'])) ?></p>
    <?php endif ?>

    <!-- TODO: this is not the best way to do it -->

    <h3 class="col-start-2 row-start-1 text-3xl font-medium">Items</h3>
    <p class="col-start-2 row-start-2"> <?php echo nl2br(htmlspecialchars(str_replace(',', "\n", $order['order_items']))); ?></p>
    <p class="col-start-2 row-start-end self-end">Total order price: <?php out($order['total_item_price']) ?></p>


  </div>
<?php endforeach ?>


<?= $pagination_buttons ?>




<?php
require_once __DIR__ . '/_footer.php';
?>
