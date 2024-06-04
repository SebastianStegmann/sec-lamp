<?php 
$result_pr_page = 10;
$page = isset($_GET['p']) ? intval($_GET['p']) : 1;
$offset = ($page-1) * $result_pr_page;

switch ($tag) {
    // does this need to be fix now that i changed the orders page???
    case "orders":
        $q = $db->prepare('SELECT COUNT(*) FROM order_view_admin');
        break;
    case "customers":
        $q = $db->prepare('SELECT COUNT(*) FROM users WHERE user_role_fk = 3');
        break;
    case "employees":
        $q = $db->prepare('SELECT COUNT(*) 
                FROM users
                WHERE user_role_fk = 4');
        break;
    case "partners":
        $q = $db->prepare('SELECT COUNT(*) 
                FROM users
                WHERE user_role_fk = 2');
            break;

            // does this need to be fix now that i changed the orders page???
    case "my_orders":
        $q = $db->prepare('SELECT COUNT(*) 
                FROM users
                WHERE user_id = :user_id');

        $q->bindParam(':user_id', $_SESSION['user_id']);
            break;        

    
}


$q->execute();
$total_rows = $q->fetch();
$count = intval($total_rows['COUNT(*)']);
$total_pages = ceil($count / $result_pr_page);
// var_dump($total_rows); 




// var_dump($total_pages); 

// KUN VIS HVIS MERE END 1 page
// if (isset($_GET['sort'])) {
//     $count = intval($total_rows['COUNT(*)']);
// } else {   
   
// }

// links
$prev_link = "?p=" . ($page - 1);
$next_link = "?p=" . ($page + 1);
if (isset($_GET['sort'])) {
    $next_link .= "&sort=" . $_GET['sort'];
    $prev_link .= "&sort=" . $_GET['sort'];

}
    




if ($total_pages >= 1){

    $pagination_buttons = "<div class='" . ($total_pages <= 1 ? 'hidden' : '') ."' ><ul class='w-1/3 grid grid-cols-4'>
    <li><a href='?p=1'>First</a></li>
    <li class='" . ($page <= 1 ? 'disabled' : '') . "'>
    <a href='" . ($page <= 1 ? '#' : $prev_link) . "'>Prev</a>
    </li>
    <li class='" . ($page >= $total_pages ? 'disabled' : '') . "'>
    <a href='"  . ($page >= $total_pages ? '#' : $next_link) ."'>Next</a>
    </li>
    <li><a href='?p=" . $total_pages . "'>Last</a></li>
    </ul></div>";
}


?>