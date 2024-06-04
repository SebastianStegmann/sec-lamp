<?php require_once __DIR__ . '/../_.php'; ?>
<!-- Redirect if not logged in and if not on index or login page -->
<?php if (!isset($_SESSION['user']) && $_SERVER['REQUEST_URI'] !== '/login' && $_SERVER['REQUEST_URI'] !== '/' && $_SERVER['REQUEST_URI'] !== '/signup') {
  header('Location: /login');
  exit(); // Make sure to exit after sending the Location header
} ?>


<?php
// limit who can view profiles
if (_check_role('1') && isset($_GET['user']) == null && strpos($_SERVER['REQUEST_URI'], '/profile') === true) {
  header('Location: /admin');
  exit();
}
if (!_check_role('1') && isset($_GET['user']) && strpos($_SERVER['REQUEST_URI'], '/profile') === true) {
  header('Location: /profile');
  exit();
}
if (_check_role('1') && !isset($_GET['user']) && strpos($_SERVER['REQUEST_URI'], '/profile') === true) {
  header('Location: /login');
  exit();
}


?>
<?php include_once __DIR__ . '/_language.php' ?>
<?php include_once __DIR__ . '/_dictionary.php' ?>

<?php

// change role for dev purposes
if (isset($_GET['role'])) {
  switch ($_GET['role']) {
    case 1:
      $_SESSION['user']['user_role_fk'] = "1";
      break;
    case 2:
      $_SESSION['user']['user_role_fk'] = "2";
      break;
    case 3:
      $_SESSION['user']['user_role_fk'] = "3";
      break;
    case 4:
      $_SESSION['user']['user_role_fk'] = "4";
      break;
    default:
      # code...
      break;
  }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

  <script src="/app.js" defer></script>
  <script src="/validator.js" defer></script>


  <link rel="stylesheet" href="/app.css">

  <title> Company | <?php echo (!isset($page_title) ? 'The best' : $page_title) ?></title>

</head>

<body class="
bg-bkg-1 text-text [&_input]:border-border
dark overflow-x-hidden
w-full h-screen text-base font-roboto font-light 
[&_input]:h-8 [&_input]:border  [&_input]:rounded-md [&_input]:outline-none
[&_input]:bg-button_bg
">


  <header class="md:[&>*]:ml-4 z-50 top-0 w-full h-12 px-4 bg-bkg-2
grid grid-cols-[1fr,1fr,1fr,1fr]
 md:grid-cols-[1fr,192px,576px,1fr] 
 lg:grid-cols-[1fr,192px,768px,1fr]
 ">


    <a href="/" class="flex  items-center w-44 h-12
md:col-start-2 md:col-span-1""
 ">
      <span class="material-symbols-outlined font-thin mr-1">
        dashboard
      </span>
      <?= $dictionary["home_$lg"] ?>
    </a>
    <!--   
    <form action="/search-results" method="GET" class="relative flex items-center">
      <input name="query" type="text" class="pl-7 bg-slate-200">
      <button class="absolute flex items-center">
        <span class="material-symbols-outlined ml-1 font-thin">
          search
        </span>            
      </button>
    </form> -->

    <button onclick="toggle_theme()">
      <span id="btn_change_theme" class="material-symbols-outlined ml-1 font-thin">
        light_mode
      </span>
    </button>


    <?php require_once __DIR__ . '/cart.php'; ?>


    <button id="btn_sidebar" class=" md:hidden flex items-center ml-auto" aria-controls="sidebar">
      <span class="material-symbols-outlined font-thin">
        menu
      </span>
    </button>

  </header>


  <!-- removed w-full h-full -->
  <main class="relative 
grid grid-cols-[1fr,192px]
 md:grid-cols-[1fr,192px,576px,1fr] 
 lg:grid-cols-[1fr,192px,768px,1fr]">




    <nav id="sidebar" class="z-40 right-0 sm:left-0 flex flex-col gap-4 w-48 h-screen md:h-auto py-4
   
 transition-transform  translate-x-full md:translate-x-0
backdrop-blur-xl 
md:col-start-2 md:col-span-1" aria-label="Sidebar">


      <div class="flex flex-col 
 [&_a]:p-2  [&_a]:rounded-xl [&_a]:mx-2  hover:[&_a]:bg-button_bg_hover">
        <?php if (_check_role('1')) : ?>

          <a href="/admin" class="flex items-center <?php out($page_title == 'Admin' ? 'bg-button_bg_hover' : '') ?>">
            <span class=" overflow-hidden material-symbols-outlined mr-2 font-thin">
              shield_person
            </span>
            Admin
          </a>
          <a href="/customers" class="flex items-center <?php out($page_title == 'Customers' ? 'bg-button_bg_hover' : '') ?>">
            <span class="material-symbols-outlined mr-2 font-thin">
              group
            </span>
            Customers
          </a>
          <a href="/employees" class="flex items-center <?php out($page_title == 'Employees' ? 'bg-button_bg_hover' : '') ?>">
            <span class="material-symbols-outlined mr-2 font-thin">
              engineering
            </span>
            Employees
          </a>
          <a href="/partners" class="flex items-center <?php out($page_title == 'Partners' ? 'bg-button_bg_hover' : '') ?>">
            <span class="material-symbols-outlined mr-2 font-thin">
              handshake
            </span>
            Partners
          </a>
          <a href="/orders" class="flex items-center <?php out($page_title == 'Orders' ? 'bg-button_bg_hover' : '') ?>">
            <span class="material-symbols-outlined mr-2 font-thin">
              storefront
            </span>
            Orders
          </a>

        <?php endif ?>

        <?php if (_check_role('2')) : ?>

          <a href="/profile" class="flex items-center">
            <span class="material-symbols-outlined mr-2 font-thin">
              group
            </span>
            View profile
          </a>
          <a href="/orders" class="flex items-center">
            <span class="material-symbols-outlined mr-2 font-thin">
              storefront
            </span>
            All orders
          </a>

        <?php endif ?>

        <?php if (_check_role('3')) : ?>

          <a href="/profile" class="flex items-center">
            <span class="material-symbols-outlined mr-2 font-thin">
              group
            </span>
            View profile
          </a>
          <a href="/orders" class="flex items-center">
            <span class="material-symbols-outlined mr-2 font-thin">
              payments
            </span>
            My orders
          </a>

        <?php endif ?>


        <?php if (_check_role('4')) : ?>

          <a href="/profile" class="flex items-center">
            <span class="material-symbols-outlined mr-2 font-thin">
              group
            </span>
            View profile
          </a>
       

        <?php endif ?>

        <?php if (!isset($_SESSION['user'])) : ?>

          <a href="/login" class="flex items-center">
            <span class="material-symbols-outlined mr-2 font-thin">
              login
            </span>
            Login
          </a>
          <a href="/signup" class="flex items-center">
            <span class="material-symbols-outlined mr-2 font-thin">
              key
            </span>
            Signup
          </a>

        <?php else : ?>

          <a href="/logout" class="flex items-center">
            <span class="material-symbols-outlined mr-2 font-thin">
              lock
            </span>
            Logout
          </a>

        <?php endif ?>
      </div>



      <!-- #### LANG ##### -->
      <div class=" relative flex flex-col items-center z-50 mx-2
  [&_button]:p-2  [&_button]:w-full  hover:[&_button]:bg-button_bg_hover
  [&_svg]:ml-1 [&_svg]:mr-3  [&_svg]:rounded-full">

        <button id="language_button_background" onclick="toggle_language_dropdown()" class="h-10 flex items-center 
    rounded-xl" tabindex="0">
          <!-- echos the value of session[lang] 'en' => 'svg ...' -->
          <div class="w-full flex items-center">
            <?php echo $languages[$_SESSION['lang']] ?>
          </div>
          <svg class="h-10 w-10   text-text/30" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path class="" fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
          </svg>
        </button>
        <div id="language_dropdown" class="hidden items-center
    absolute w-full flex-col top-8 m-2 rounded-b-xl">

          <!-- for each langauge($shortcode) in languages AA and their respective value($svg) -->
          <?php foreach ($languages as $shortcode => $svg) : ?>
            <!-- css class hidden if same langauge, else flex -->
            <a class="w-full <?php out($_SESSION['lang'] == $shortcode ? 'hidden' : '') ?>" href="<?php echo '?lang=' . ($_SESSION['lang'] == $shortcode ? 'en' : $shortcode) ?>" <?php out($_SESSION['lang'] == $shortcode ? "tabindex='-1'" : '') ?>>
              <div class="items-center h-10 p-2 flex">
                <?php echo $_SESSION['lang'] == $shortcode ? $languages['en'] : $svg ?>

              </div>
            </a>

          <?php endforeach ?>


        </div>
      </div>
      <a class="mt-5 flex items-center hover:bg-button_bg_hover p-2 rounded-xl mx-2" href="/new-partner">
        <span class="material-symbols-outlined mr-2 font-thin">
          Approval_Delegation
        </span>
        Become a partner
      </a>
    </nav>





    <div class="mt-4 pb-12 mx-4 px-4 bg-bkg-2 rounded-md text-text
-order-1
md:order-none
w-[calc(100%-2rem+192px)]
md:w-[calc(100%-2rem)]
h-fit
">