<?php
$frm_search_url = "api-search-employees.php";
$tag = 'employees';
$page_title = 'Employees';
require_once __DIR__ . '/_header.php';
if( ! _check_role('1')){
    header('Location: /login'); 
    exit();
  }

$db = _db();
require_once __DIR__ . '/_pagination.php';

$q = $db->prepare(' SELECT user_id, user_name, 
                    user_last_name, user_email, employee_salary, user_tag_color, user_deleted_at, user_is_blocked
                    FROM users INNER JOIN employees 
                    ON user_id = employee_id 
                    WHERE user_role_fk = 4 
                    LIMIT :limit OFFSET :offset');

// part of pagination
$q->bindParam(':limit', $result_pr_page, PDO::PARAM_INT);
$q->bindParam(':offset', $offset, PDO::PARAM_INT);

$q->execute();
$users = $q->fetchAll();

?>



<div class="grid grid-cols-[max-content,1fr] md:grid-cols-[max-content,15fr,22fr] py-4 ">
    <!-- title -->
    <h1 class="text-xl">
        Employees
    </h1>

    <div class="col-span-2 w-full md:col-span-1 md:col-start-3 md:row-start-1">
        <?php include_once __DIR__ . '/_search_for.php' ?>
    </div>
</div>

<?php if (!$users) : ?>
    <h1>No employees in the system</h1>
<?php endif ?>

<?php foreach ($users as $user) : ?>
    <div class="grid justify-items-between grid-cols-1 xs:grid-cols-2 md:grid-cols-[7fr,2fr]  border-border  border-b md:grid-rows-1 py-2  ">
        <div class="grid grid-rows-2 grid-cols-[3fr,30fr]">
            <!-- Hidden user id -->
            <div class="div_user_id hidden"><?php out($user['user_id']) ?></div>
            <!-- Cirle -->
            <div class="grid self-center mr-1 place-self-center row-span-2 items-center justify-center w-8 h-8  text-sm rounded-full" style="background-color: <?php out($user['user_tag_color']); ?>">
                <!-- letter in circle -->
                <p class=""> <?php out($user['user_name'][0]) ?> </p>
            </div>

            <p class=""><?php out($user['user_name'] . ' ' . $user['user_last_name']) ?></p>
            <p class=""><?php out($user['user_email']) ?></p>

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


<?= $pagination_buttons ?>

<?php
require_once __DIR__ . '/_footer.php';
?>