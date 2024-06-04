<?php
if (isset($_SESSION['user'])) {
  header('Location: /');
  exit();
}
require_once __DIR__.'/_header.php';


?>
<form onsubmit="validate(login); return false" method="POST" class="flex flex-col gap-4 md:w-1/2 lg:w-1/3  h-full m-auto py-4">
    <h1 class="text-2xl pt-4">Login</h1>
    <label class="flex justify-between" for="user_email">Email address</label>
    <input name="user_email" type="text" placeholder="email" value="admin@company.com" data-validate="email">
    <label class="flex justify-between" for="user_password">Password</label>
    <input value="password" name="user_password" type="text" placeholder="password"
      data-validate="str" data-min="<?= USER_PASSWORD_MIN ?>" data-max="<?= USER_PASSWORD_MAX ?>"
      class="">
    <button class="w-full h-10 bg-sky-600 text-white rounded-md">Login</button>
  </form>
<?php require_once __DIR__.'/_footer.php';?>