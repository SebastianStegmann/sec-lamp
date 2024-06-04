<?php
require_once __DIR__.'/_header.php';
$tag = $_GET['source'];
$db = _db();


if ($tag == 'orders') {
    $q = $db->prepare('SELECT *
    FROM order_view_admin  
    WHERE (order_id LIKE :word COLLATE NOCASE 
    OR user_name LIKE :word COLLATE NOCASE
    OR user_last_name LIKE :word COLLATE NOCASE
    OR user_email LIKE :word COLLATE NOCASE
    OR order_items LIKE :word COLLATE NOCASE)
    AND user_id LIKE :user_id
    AND partner_id LIKE :partner_id
    ');

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

} else {
    $q = $db->prepare(' SELECT * FROM users 
                        WHERE (user_name LIKE :word COLLATE NOCASE 
                        OR user_last_name LIKE :word COLLATE NOCASE)
                        AND user_role_name = :source ');

                        $q->bindValue(':source', substr($_GET['source'], 0, -1));
}

                                       
$q->bindValue(':word',  '%' . $_GET['query'] . '%');

$q->execute();
$users = $q->fetchAll();
?>

<div class="grid grid-cols-[max-content,1fr] md:grid-cols-[max-content,15fr,22fr] py-4 ">
  <?php if ( ! isset($_GET['query']) || empty($_GET['query'])) {
    echo '<h1 class="text-xl">No search input</h1>'; exit(); 
  }?>
    <!-- title -->
    <h1 class="text-xl">
        Search results: <?php echo $_GET['source']; ?>
    </h1>
    <div class="col-span-2 w-full md:col-span-1 md:col-start-3 md:row-start-1">
        <?php include_once __DIR__ . '/_search_for.php' ?>
    </div>
</div>
<?php if ( ! $users) out('No result, try something else') ?>

<?php if ($tag != 'orders'): ?>
<?php foreach($users as $user): ?>
  <div class="grid justify-items-between grid-cols-1 xs:grid-cols-2 md:grid-cols-[7fr,2fr]  border-border  border-b md:grid-rows-1 py-2  ">
        <div class="grid grid-rows-2 grid-cols-[3fr,30fr]">
          
            <!-- Hidden user id -->
            <div class="div_user_id hidden"><?php out($user['user_id']) ?></div>
            <!-- Cirle -->
            <div class="grid self-center mr-1 place-self-center row-span-2 items-center justify-center w-8 h-8  text-sm rounded-full" style="background-color: <?php out($user['user_tag_color']); ?>">
                <!-- letter in circle -->
                <p class=""> <?php out($user['user_name'][0]) ?> </p>
            </div>

            <p class=""><?php out($user['user_name'] . ' ' . $user['user_last_name']) ?> </p>
            <p class=""><?php out($user['user_email']  . ' — ' . $user['user_role_name']) ?></p>
           
        </div>

        <!-- div around buttons for flex wrap -->
        <div class="grid grid-rows-1 grid-cols-3 xs:justify-items-end self-center ">
            <button class="user_block_button " data-user-blocked='<?php out($user['user_is_blocked']) ?>' data-user-id='<?php out($user['user_id']) ?>'>
                <span class="block-button-span material-symbols-outlined font-thin">
                    <!-- Shows the right icon initially -->
                    <?= $user['user_is_blocked'] ? 'lock' : 'lock_open' ?>
                </span>
            </button>

            <!-- set user[session][view_user] = user_id on click -->
            <button class="">
                <span class="material-symbols-outlined font-thin">
                    <a href="/profile/<?php out($user['user_id']) ?>">edit_note</a>
                </span>
            </button>

            <button onclick="delete_user(event)" class="btn_user_delete" data-user-deleted='<?= out($user['user_deleted_at'] != 0 ? 1 : 0) ?>'>
                <span class="material-symbols-outlined font-thin">
                    <!-- delete -->
                    <?php out($user['user_deleted_at'] != 0 ? 'settings_backup_restore' : 'delete') ?>
                </span>
            </button>
        </div>
    </div>
<?php endforeach ?>
<?php else: ?>
    <?php foreach($users as $user): ?>
    <div class="grid grid-cols-2 gap-0 border-b border-b-slate-200 py-2 ">
      <p class=" hidden"><?= $user['user_id'] ?></p>
      <p class="col-start-1 text-3xl font-medium"><?php out($user['user_name'] . " " . $user['user_last_name']);?></p>
      <p class=" font-extralight text-sm ">Order id: <?php out($user['order_id']) ?></p>
      <!-- <p class="col-start-1">Customer info: </p> -->
      <p class="col-start-1 "><?php out($user['user_email']) ?></p>
      <p class="col-start-1"><?php out($user['user_address']) ?></p>
      <!-- TODO: this is not the best way to do it -->
      <h3 class="col-start-2 row-start-1 text-3xl font-medium" >Items</h3>
      <p class="col-start-2 row-start-2"> <?php echo nl2br(htmlspecialchars(str_replace(',', "\n", $user['order_items']))); ?></p>
      <p class="col-start-2 row-start-end self-end">Total order price: <?php out($user['total_item_price']) ?></p>

    </div>
  <?php endforeach ?>
<?php endif ?>


<?php
require_once __DIR__.'/_footer.php';
?>
