<?php
require_once __DIR__ . '/_header.php';


// if admin then not

// add only for standard profile not for the profile?user=


// if role not admin and there is a ?user=x string, remove it
// even though it wont display info if not admin
// if( ! _check_role('admin') && isset($_GET['user'])){
//     header('Location: /profile'); 
//     exit();
//   }


// $user_name = '<script>alert()</script>'; // This came from the database/API
// $user_name = '<script>document.querySelector("body").style.backgroundColor="gray"</script>'; // This came from the database/API

// find the user that is logged in


// require_once __DIR__.'/../_.php';
$db = _db();

$q = $db->prepare(' SELECT user_id, user_name, 
                      user_last_name, user_email, user_tag_color, user_profile_picture_fk, user_address, user_deleted_at, user_is_blocked, user_updated_at
                      FROM users
                      WHERE user_id = :user_id;');



if (_check_role('1')) {
  $profile_id = $_GET['user'];
} else {
  // session[user][visited_user]
  $profile_id = $_SESSION['user']['user_id'];
}

$q->bindValue(':user_id',  $profile_id);

$q->execute();

$user = $q->fetch();
$q = $db->prepare('
      SELECT profile_picture_path
      FROM profile_pictures
      WHERE profile_picture_id = :profile_picture_id;
    ');

$q->bindValue(':profile_picture_id',  $user['user_profile_picture_fk']);
$q->execute();

$profile_picture = $q->fetch();

var_dump($user);

?>

<div class="flex py-4 text-xl">
  <h1 class="">
    Profile
  </h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-[1fr,1fr] mr-4 rounded-md">
  <div>
    <p><?php out($user['user_name'] . ' ' . $user['user_last_name']) ?></p>
    <p><?php out($user['user_email']) ?></p>
    <p><?php out($user['user_address']) ?></p>

    <?php
    $q = $db->prepare(' SELECT employee_salary, employee_hourly_pay
        FROM employees
        WHERE employee_id = :user_id;');

    if (_check_role('1')) {
      $q->bindValue(':user_id',  $_GET['user']);
    } else {
      // session[user][visited_user]
      $q->bindValue(':user_id',  $_SESSION['user']['user_id']);

    }
    $q->execute();

    $salary = $q->fetch();

    var_dump($salary);
    ?>
    <?php if ($_SESSION['user']['user_role_fk'] == '1') : ?>
      <button class="" onclick="raise(1000, <?php out($profile_id) ?>,<?php out($salary['employee_hourly_pay']) ?>)"> Give a raise</button>
    <?php endif ?>

    <?php if (isset($_SESSION) && $_SESSION['user']['user_role_fk'] == "1") : ?>
      <p>Admin info</p>
      <p><?php out($user['user_is_blocked'] != 0 ? 'This user is blocked' : 'This user is not blocked') ?></p>
      <p><?php out($user['user_updated_at'] != 0 ? ' This user was last updated at ' . date("d F Y", $user['user_updated_at']) : 'This user has never been updated') ?> </p>
    <?php endif; ?>

    <button onclick="profile_button('btn_update_user_info')" class="bg-button_bg border border-border  py-1 px-2 rounded hover:bg-button_bg_hover   hover:text-button_text_hover">Edit profile</button>
    <button onclick="profile_button('btn_update_user_password')" class="bg-button_bg border border-border  py-1 px-2 rounded hover:bg-button_bg_hover   hover:text-button_text_hover">Change password</button>
    <?php if ($_SESSION['user']['user_role_fk'] != "1") : ?>
      <button onclick="profile_button('btn_delete_user')" class="bg-button_bg border border-border  py-1 px-2 rounded hover:bg-button_bg_hover   hover:text-button_text_hover">Delete profile</button>
    <?php endif ?>
  </div>

  <div>
    <!-- ################## UPDATE USER ################## -->
    <form id="frm_update_user_info" onsubmit="validate(update_user); return false" method="POST"
      enctype="multipart/form-data"
      class="hidden flex-col py-4 mx-auto gap-4">
      
      <input type="hidden" name="MAX_FILE_SIZE" value="2097152">

      <div class="grid">
        <label class="flex justify-between" for="user_profile_picture">
          <span class="">profile picture</span>
        </label>
        <img src="<?php echo '../uploads/' . $profile_picture['profile_picture_path']; ?>">
        <input type="file" name="user_profile_picture" id="user_profile_picture" accept="image/*" data-validate="file" class="">
      </div>


      <!-- ?? VALIDATE USER ID ?? TODO -->
      <div class="hidden">
        <label class="flex justify-between" for="user_name">
          <span class="">id</span>
        </label>
        <input value="<?php out($user['user_id']) ?>" id="user_id" name="user_id" type="text" data-validate="str" class="">
      </div>

      <div class="grid">
        <label class="flex justify-between" for="user_name">
          <span class=""> name</span> <span>(<?= USER_NAME_MIN ?> to <?= USER_NAME_MAX ?> characters)</span>
        </label>
        <input value="<?php out($user['user_name']) ?>" id="user_name" name="user_name" type="text" data-validate="str" data-min="<?= USER_NAME_MIN ?>" data-max="<?= USER_NAME_MAX ?>" class="">
      </div>

      <div class="grid">
        <label class="flex justify-between" for="user_last_name">
          <span class="">last name</span> <span>(<?= USER_LAST_NAME_MIN ?> to <?= USER_LAST_NAME_MAX ?> characters)</span>
        </label>
        <input value="<?php out($user['user_last_name']) ?>" id="user_last_name" name="user_last_name" type="text" data-validate="str" data-min="<?= USER_LAST_NAME_MIN ?>" data-max="<?= USER_LAST_NAME_MAX ?>" class="">
      </div>


      <div class="grid">
        <label for="">
          <span class="">email</span>
        </label>
        <input value="<?php out($user['user_email']) ?>" name="user_email" type="text" data-validate="email">
      </div>

      <div class="grid">
        <label for="">
          <span class="">Address</span>
        </label>
        <input value="<?php out($user['user_address']) ?>" name="user_address" type="text" data-validate="str" data-min="<?= USER_ADDRESS_MIN ?>" data-max="<?= USER_ADDRESS_MAX ?>
        class="">
      </div>
  
      
      <button class=" w-full h-10 bg-button_bg rounded-md">Update</button>

        <!-- ################## UPDATE USER PASSWORD ################## -->

    </form>
    <!-- TODO: EDIT USER -->
    <!-- TODO: DELETE USER -->
    <!-- TODO: CHANGE PASSWORD -->
    <form id="frm_update_user_password" onsubmit="validate(update_user_password); return false" class="hidden flex-col py-4
     mx-auto gap-4  ">


      <div class="grid">
        <label class="flex justify-between" for="">
          <span class="">Current password</span>
        </label>
        <input placeholder="Current password" value="" name="current_user_password" type="text" data-validate="str" data-min="<?= USER_PASSWORD_MIN ?>" data-max="<?= USER_PASSWORD_MAX ?>" class="">
      </div>

      <div class="grid">
        <label class="flex justify-between" for="">
          <span class="">New password</span> <span>(<?= USER_PASSWORD_MIN ?> to <?= USER_PASSWORD_MAX ?> characters)</span>
        </label>
        <input placeholder="New password" value="" name="user_password" type="text" data-validate="str" data-min="<?= USER_PASSWORD_MIN ?>" data-max="<?= USER_PASSWORD_MAX ?>" class="">
      </div>

      <div class="grid">
        <label for="">
          <span class="">Confirm new password</span>
        </label>
        <input placeholder="Repeat new password" value="" name="user_confirm_password" type="text" data-validate="match" data-match-name="user_password" class="">
      </div>

      <button class="w-full h-10 bg-button_bg  rounded-md">Update</button>
    </form>




    <!-- ################## DELETE USER ################## -->
    <form method="POST" action="/api/api-delete-user.php" onsubmit="confirm('are you sure you want to delete your user'); return" id="frm_delete_user" class="hidden flex-col py-4
    mx-auto gap-4 ">

      <p>BACKEND VERIFICATION NOT SET UP YET</p>
      <div class="grid">
        <label class="flex justify-between" for="">
          <span class="">Current password</span>
        </label>
        <input placeholder="Current password" value="" name="current_user_password" type="text" data-validate="str" data-min="<?= USER_PASSWORD_MIN ?>" data-max="<?= USER_PASSWORD_MAX ?>" class="">
      </div>
      <button class="w-full h-10 bg-button_bg  rounded-md">Delete profile</button>
    </form>

  </div>
</div>

<?php
require_once __DIR__ . '/_footer.php';
?>