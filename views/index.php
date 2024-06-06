<?php
require_once __DIR__ . '/_header.php';

$tag = 'partners';
$db = _db();
require_once __DIR__ . '/_pagination.php';

#TODO Hent kun dem som har ikke er hidden
#
# Først select fra restaurants table
# Se hvem der er hidden
#

$q = $db->prepare('SELECT partner_id FROM partners WHERE partner_hidden = 1');
$q->execute();
$hidden = $q->fetchAll();
$hidden_ids = array();
foreach ($hidden as $row) {
    $hidden_ids[] = $row['partner_id'];
}


$q = $db->prepare(' SELECT user_id, user_name, user_last_name, user_email, user_tag_color, user_is_blocked, user_deleted_at
                    FROM users WHERE user_role_fk = 2 LIMIT :limit OFFSET :offset');

$result_pr_page = $result_pr_page + 100;

// part of pagination
$q->bindParam(':limit', $result_pr_page, PDO::PARAM_INT);
$q->bindParam(':offset', $offset, PDO::PARAM_INT);

$q->execute();
$partners = $q->fetchAll();

# if not admin remove id from $partners

if (!_check_role('1')) {
    foreach ($partners as $key => $partner)
        if (in_array($partner['user_id'], $hidden_ids)) {
            unset($partners[$key]);
        }
}
?>

<?php foreach ($partners as $partner) : ?>

    <div class="grid ">
        <a href="restaurant/<?php out($partner['user_id']) ?>"><?php out($partner['user_name']) ?><?php echo (in_array($partner['user_id'], $hidden_ids)) ? '(hidden)' : ''; ?></a>
    </div>
<?php endforeach ?>


<?php
require_once __DIR__ . '/_footer.php';
?>
