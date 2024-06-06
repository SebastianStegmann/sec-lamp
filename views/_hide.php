<?php

require_once __DIR__ . '/../_.php';
$db = _db();

if (_check_role('1')) {
  $profile_id = $_GET['user'];
} else {
  // session[user][visited_user]
  $profile_id = $_SESSION['user']['user_id'];
}

$q = $db->prepare('SELECT * FROM partners WHERE partner_id = :partner_id');
$q->bindValue(':partner_id',  $profile_id);
$q->execute();

$partner = $q->fetch();
?>

<form method="POST" action="/api/api-hide-partner.php" onsubmit="" id="frm_hide_partner" class="flex-col py-4
    mx-auto gap-4 ">
  <input value="<?php out($partner['partner_hidden']); ?>" name="hide_bool" type="text" class="hidden">

  <?= $_SESSION['user']['user_id'] == 1 ?  "<input value='{$partner['partner_id']}' name='user_id' type='text' class='hidden'>" : ''; ?>
  <button><?php out($partner['partner_hidden'] == 0 ? 'Hide' : 'Unhide'); ?></button>

</form>
