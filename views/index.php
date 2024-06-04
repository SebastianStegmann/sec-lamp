<?php
require_once __DIR__ . '/_header.php';

$tag = 'partners';
$db = _db();
require_once __DIR__ . '/_pagination.php';


$q = $db->prepare(' SELECT user_id, user_name, user_last_name, user_email, user_tag_color, user_is_blocked, user_deleted_at
                    FROM users WHERE user_role_fk = 2 LIMIT :limit OFFSET :offset');



// part of pagination
$q->bindParam(':limit', $result_pr_page, PDO::PARAM_INT);
$q->bindParam(':offset', $offset, PDO::PARAM_INT);

$q->execute();
$partners = $q->fetchAll();
?>

<?php foreach ($partners as $partner) : ?>

    <div class="grid ">
        <a href="restaurant/<?php out($partner['user_id']) ?>"> <?php out($partner['user_name']) ?></a>
    </div>
<?php endforeach ?>


<?php
require_once __DIR__ . '/_footer.php';
?>