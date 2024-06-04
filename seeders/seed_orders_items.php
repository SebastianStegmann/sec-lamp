<?php
require_once __DIR__.'/../_.php';
require_once __DIR__ . '/Faker/autoload.php';
$faker = Faker\Factory::create();

try{

  $db = _db()
  ;
  // Get order's id
  $q = $db->prepare('SELECT order_id FROM orders');
  $q->execute();
  $orders_ids = $q->fetchAll(PDO::FETCH_COLUMN);

  // Get items_ids to assign to order
  $q = $db->prepare("SELECT item_id, item_price, item_created_by_user_fk FROM items");
  $q->execute();
  $items = $q->fetchAll(PDO::FETCH_NUM);


  // echo json_encode($items); exit();

  $db = _db();
  $q = $db->prepare('DROP TABLE IF EXISTS orders_items');
  $q->execute();

  $q = $db->prepare('
    CREATE TABLE orders_items(
      orders_items_id             BIGINT UNSIGNED AUTO_INCREMENT,
      orders_items_order_fk       BIGINT UNSIGNED,
      orders_items_item_fk        BIGINT UNSIGNED,
      orders_items_item_price     decimal(10,2),
      orders_items_item_quantity  smallint(5),
      orders_items_created_at     CHAR(10),
      orders_items_updated_at     CHAR(10),
      orders_items_deleted_at     CHAR(10),
      PRIMARY KEY (orders_items_id),
      FOREIGN KEY (orders_items_order_fk) REFERENCES orders(order_id),
      FOREIGN KEY (orders_items_item_fk) REFERENCES items(item_id)
    )
  ');
  $q->execute();
  
  $orders_items = [];
  $haystack = [];
  $values = '';
  for($i = 0; $i < 150; $i++){
    // makes it pick the same item id and price, before it picked a random id and a random price not related to eachother
   
    $orders_items_id = bin2hex(random_bytes(16));
    // $orders_items_order_fk = $orders_ids[array_rand($orders_ids)];

    // ensure that every order gets seeded with at least one item
    // then add extra items
    if ($i <= 99 ){
      $orders_items_order_fk = $orders_ids[$i];

      $pick_item = $items[array_rand($items)];

      $orders_items_item_fk = $pick_item[0];

     
      // bind all order_ids with the creator of the item inserted
      $store_order_fk_and_item_created_by_fk[$orders_items_order_fk] = $pick_item[2];

    }

    if ($i >= 100) {
      $orders_items_order_fk = $orders_ids[array_rand($orders_ids)];

      // get all items made by the same partner as last item inserted
      $q = $db->prepare('SELECT item_id, item_price FROM items
                         WHERE item_created_by_user_fk = :existing_item_creator');

      // insert the item_created_by_user_fk that was stored in store_order_fk_and_item_created_by_fk
      $q->bindParam(':existing_item_creator', $store_order_fk_and_item_created_by_fk[$orders_items_order_fk]);
      $q->execute();
      
      $same_partner_items = $q->fetchAll(PDO::FETCH_NUM);

      $pick_item = $same_partner_items[array_rand($same_partner_items)];
      // $pick_item --- men fra ud fra vores nye array

      $orders_items_item_fk = $pick_item[0];

    }

    // old line, which picked an id not related to the price
    // $orders_items_item_fk = $items[array_rand($items)][0];



    // Same order with same item cannot repeat
    $order_item = $orders_items_order_fk.$orders_items_item_fk;
    if( in_array($order_item, $orders_items) ){
      $i--;
      continue;
    }
    // pick_item[2] is the item_created_by_user_fk 
    // aka the id of the partner that created that item
    // check if the current items creater is the same as the already added one


    array_push($orders_items, $order_item);
     // old line, which picked a price not related to the id
    // $orders_items_item_price = $items[array_rand($items)][1];
    $orders_items_item_price = $pick_item[1];

    
    $orders_items_item_quantity = rand(1, 5);
    $orders_items_created_at = rand(time()-1602343484, time());
    $orders_items_updated_at = 0;
    $orders_items_deleted_at = 0;

    $values .= "(null, '$orders_items_order_fk', '$orders_items_item_fk',
                  $orders_items_item_price, $orders_items_item_quantity, $orders_items_created_at,
                  $orders_items_updated_at, $orders_items_deleted_at),";
  }
  $values = rtrim($values, ',');  
  $q = $db->prepare("INSERT INTO orders_items VALUES $values");
  $q->execute();

  echo "+ orders_items".PHP_EOL;
}catch(Exception $e){
  echo $e;
}









