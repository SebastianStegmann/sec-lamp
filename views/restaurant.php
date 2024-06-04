<?php
require_once __DIR__.'/_header.php';



$db = _db();

$q = $db->prepare("SELECT * FROM items
                   WHERE item_created_by_user_fk LIKE :partner_id
                   ");


// if customer only show own orders


// If partner only show orders of own products

$partner_id = $_GET['partner'];

$q->bindValue(':partner_id',  $partner_id);

// part of pagination


$q->execute();
$items = $q->fetchAll();    

?>

<?php foreach($items as $item) :?>
  <div>
    <h2><?php out($item['item_name'])?></h2>
    <button onclick="add_to_cart('<?php echo $item['item_id']?>')">Add to cart</button>
  </div>
<?php endforeach ?>

<?php
require_once __DIR__.'/_footer.php';
?>
