<?php
require_once __DIR__.'/_header.php';

// $user_name = '<script>alert()</script>'; // This came from the database/API
// $user_name = '<script>document.querySelector("body").style.backgroundColor="gray"</script>'; // This came from the database/API

?>

 

<form onsubmit="validate(new_partner); return false" 
class="flex flex-col py-4
md:w-1/2 lg:w-1/3 mx-auto gap-4 [&_label]:text-gray-500 [&_input]:px-2 [&_input]:border 
[&_input]:border-gray-600 [&_input]:h-10 [&_input]:rounded-md [&_input]:outline-none">
<h1 class="text-2xl pt-4">Apply to become a partner</h1>
    <div class="grid">
      <label class="flex justify-between" for="user_name">
       <span class="">Name</span> <span>(<?= USER_NAME_MIN ?> to <?= USER_NAME_MAX ?> characters)</span>
      </label>
      <input value="sebastian" id="user_name" name="user_name" type="text"
      data-validate="str" data-min="<?= USER_NAME_MIN ?>" data-max="<?= USER_NAME_MAX ?>"
      class="">
    </div>

    <div class="grid">
      <label class="flex justify-between" for="user_last_name">
        <span class="">Last name</span> <span>(<?= USER_LAST_NAME_MIN ?> to <?= USER_LAST_NAME_MAX ?> characters)</span>
      </label>    
      <input value="sebasdsaastian" id="user_last_name" name="user_last_name" type="text"
      data-validate="str" data-min="<?= USER_LAST_NAME_MIN ?>" data-max="<?= USER_LAST_NAME_MAX ?>"
      class="">
    </div>

    <div class="grid">
      <label  for="">
        <span class="">Email address</span>
      </label>    
      <input value="sebastian@gmail.com" name="user_email" type="text" 
      data-validate="email">
    </div>

    <div class="grid">
      <label class="flex justify-between" for="">
       <span class="">Password</span> <span>(<?= USER_PASSWORD_MIN ?> to <?= USER_PASSWORD_MAX ?> characters)</span>
      </label>    
      <input value="password" name="user_password" type="text"
      data-validate="str" data-min="<?= USER_PASSWORD_MIN ?>" data-max="<?= USER_PASSWORD_MAX ?>"
      class="">
    </div>

    <div class="grid">
      <label  for="">
       <span class="">Confirm password</span>
      </label>    
      <input value="password" name="user_confirm_password" type="text"
      data-validate="match" data-match-name="user_password"
      class="">
    </div>

    <!-- TODO: proper address validation -->
    <div class="grid">
      <label  for="">
       <span class="">Address</span>
      </label>    
      <input value="skodsborgvej 190" name="user_address" type="text"
      data-validate="str" data-min="<?= USER_NAME_MIN ?>" data-max="<?= USER_NAME_MAX ?>
      class="">
    </div>

    <button class="w-full h-10 bg-sky-600 text-white rounded-md">Signup</button>

  </form>

<?php
require_once __DIR__.'/_footer.php';
?>
