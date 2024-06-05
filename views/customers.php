<?php
$frm_search_url = "api-search-customers.php";
$tag = 'customers';
$page_title = 'Customers';
require_once __DIR__ . '/_header.php';

if( ! _check_role('1')){
    header('Location: /index'); 
    exit();
  }

$db = _db();
require_once __DIR__ . '/_pagination.php';

$q = $db->prepare(' SELECT user_id, user_name, user_last_name, user_email, user_tag_color, user_profile_picture_fk, user_is_blocked, user_deleted_at
                    FROM users WHERE user_role_fk = 3 LIMIT :limit OFFSET :offset');

// SORT BY DELETED OR NOT
if (!isset($_GET['sort'])) {
    $q = $db->prepare(' SELECT user_id, user_name, user_last_name, user_email, user_tag_color, user_profile_picture_fk, user_is_blocked, user_deleted_at
    FROM users WHERE user_role_fk = 3 LIMIT :limit OFFSET :offset');
} else {
    $q = $db->prepare(' SELECT user_id, user_name, user_last_name, user_email, user_tag_color, user_profile_picture_fk, user_is_blocked, user_deleted_at
    FROM users WHERE user_role_fk = 3 AND user_deleted_at BETWEEN :low AND :high LIMIT :limit OFFSET :offset');
    // set range as either between 0 and 0 or 1 and epoch max
    $low = $_GET['sort'] == 'deleted' ? 1 : 0;
    $high = $_GET['sort'] == 'deleted' ? 2147483647 : 0;

    $q->bindParam(':low', $low, PDO::PARAM_INT);
    $q->bindParam(':high', $high, PDO::PARAM_INT);
}



// part of pagination
$q->bindParam(':limit', $result_pr_page, PDO::PARAM_INT);
$q->bindParam(':offset', $offset, PDO::PARAM_INT);

$q->execute();
$users = $q->fetchAll();
?>

<div class="grid grid-cols-[max-content,1fr] md:grid-cols-[max-content,15fr,22fr] py-4 ">
    <!-- title -->
    <h1 class="text-xl">
        Customers
    </h1>
    <!-- sort -->
    <div class="[&_a]:border [&_a]:border-slate-200 [&_a]:rounded 
    self-center justify-self-end 
    md:justify-self-start md:pl-2">
        <a href="/customers">All</a href="/customers">
        <a href="?sort=deleted">Deleted</a href="/customers">
        <a href="?sort=not-deleted">Not deleted</a href="/customers">
    </div>
    <!-- search -->
    <div class="col-span-2 w-full md:col-span-1 md:col-start-3 md:row-start-1">
        <?php include_once __DIR__ . '/_search_for.php' ?>
    </div>
</div>


<?php if (!$users) : ?>
    <h1>No customers in the system</h1>
<?php endif ?>

<?php foreach ($users as $user) : 
    $profile_picture_q = $db->prepare('
        SELECT profile_picture_path
        FROM profile_pictures
        WHERE profile_picture_id = :user_profile_picture_fk
    ');
    $profile_picture_q->bindValue( ':user_profile_picture_fk', $user['user_profile_picture_fk'] );
    $profile_picture_q->execute();
    $image_path = $profile_picture_q->fetch();
    
    ?>
    <div class="grid justify-items-between grid-cols-1 xs:grid-cols-2 md:grid-cols-[7fr,2fr] border-border border-b md:grid-rows-1 py-2  ">
        <div class="grid grid-rows-2 grid-cols-[3fr,30fr]">
            <!-- Hidden user id -->
            <div class="div_user_id hidden"><?php out($user['user_id']) ?></div>
            <!-- Cirle -->
            <div class="grid self-center mr-1 place-self-center row-span-2 items-center justify-center w-8 h-8  text-sm rounded-full" style="background-color: <?php out($user['user_tag_color']); ?>">
                <!-- letter in circle -->
                <img src="../uploads/<?= $image_path['profile_picture_path']; ?>">
            </div>

            <p class=""><?php out($user['user_name'] . ' ' . $user['user_last_name']) ?></p>
            <p class=""><?php out($user['user_email']) ?></p>

        </div>

        <!-- div around buttons for flex wrap -->
        <div class="grid grid-rows-1 grid-cols-3 xs:justify-items-end self-center ">
            <button class=" user_block_button" data-user-blocked='<?php out($user['user_is_blocked']) ?>' data-user-id='<?php out($user['user_id']) ?>'>
                <span class="block-button-span material-symbols-outlined font-thin">
                    <!-- Shows the right icon initially -->
                    <?= $user['user_is_blocked'] ? 'lock' : 'lock_open' ?>
                </span>
            </button>


            <!-- set user[session][view_user] = user_id on click -->
            <a href="/profile/<?php out($user['user_id']) ?>" class="grid justify-center self-center">
                <span class="material-symbols-outlined font-thin">
                    edit_note
                </span>
            </a>

            <button onclick="delete_user(event)" class="btn_user_delete " data-user-deleted='<?= out($user['user_deleted_at'] != 0 ? 1 : 0) ?>'>
                <span class="material-symbols-outlined font-thin">
                    <!-- delete -->
                    <?php out($user['user_deleted_at'] != 0 ? 'settings_backup_restore' : 'delete') ?>
                </span>
            </button>

        </div>
    </div>
<?php endforeach ?>

<?= $pagination_buttons ?>






<?php
require_once __DIR__ . '/_footer.php';
?>