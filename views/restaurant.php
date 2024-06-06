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

$db = _db();

$q = $db->prepare("
  SELECT * FROM partners_comments
  WHERE partner_id_fk = :partner_id
");
$q->bindValue(':partner_id', $partner_id);
$q->execute();
$comments = $q->fetchAll();
?>

<?php foreach($items as $item) :?>
  <div>
    <h2><?php out($item['item_name'])?></h2>
    <button onclick="add_to_cart('<?php echo $item['item_id']?>')">Add to cart</button>
  </div>
<?php endforeach ?>
<section class="comment-section">
  <h2>Comments <span>(<?= count( $comments ); ?>)</span></h2>
  <div class="comment-wrapper">
     <?php if ( !empty( $comments ) ) {
      foreach ( $comments as $comment ) {
        $user_id = $comment['user_id_fk'];

        $q = $db->prepare("
          SELECT * FROM users
          WHERE user_id = :user_id
        ");
        $q->bindValue(':user_id', $user_id);
        $q->execute();
        $user = $q->fetch();

        $q = $db->prepare("
          SELECT profile_picture_path
          FROM profile_pictures
          WHERE profile_picture_id = :profile_picture_id
        ");
        $q->bindValue(':profile_picture_id', $user['user_profile_picture_fk']);
        $q->execute();
        $profile_picture = $q->fetch(); ?>

        <div class="comment">
          <div class="comment-container">
            <div class="profile">
              <img src="../uploads/<?= $profile_picture['profile_picture_path']; ?>">
              <div class="name"><?= $user['user_name'] . ' ' . $user['user_last_name']; ?></div>
            </div>
            <div class="text">
              <p><?= $comment['comment']; ?></p>
              <aside><?= date('d-m-Y H:i:s', $comment['comment_created_at']); ?></aside>
            </div>
          </div>
        </div>


      <?php }
     }
     else {
      echo '<p>No comments yet.</p>';
     } ?>
  </div>

  <form id="insert_restaurant_comment" method="POST" onsubmit="validate(add_comment, event)">
    <label for="user_comment">Add a new comment</label>
    <textarea name="user_comment" rows="8" required placeholder="Text..." data-validate="textarea" data-min="<?= PARTNER_COMMENT_MIN; ?>" data-max="<?= PARTNER_COMMENT_MAX; ?>"></textarea>

    <button class="bg-button_bg border border-border  py-1 px-2 rounded hover:bg-button_bg_hover hover:text-button_text_hover" type="submit">Add comment</button>
  </form>
</section>

<?php
require_once __DIR__.'/_footer.php';
?>
