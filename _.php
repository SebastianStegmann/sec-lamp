<?php
ini_set('display_errors', 1);
session_start();
// ob_start(); // to avoid headers already sent -- not good solution


// ##############################
function _db()
{
	try {
		$user_name = "root";
		// $user_password = ""; // sqlite
		$user_password = "root";
		// $db_connection = 'sqlite:' . __DIR__ . '/database/data.sqlite';
		$db_connection = "mysql:host=localhost; dbname=exam_db_1sem; charset=utf8mb4";

		// PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		//   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ   [{}]    $user->id
		$db_options = array(
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // [['id'=>1, 'name'=>'A'],[]]  $user['id']
		);
		return new PDO($db_connection, $user_name, $user_password, $db_options);
	} catch (PDOException $e) {
		throw new Exception('ups... system under maintainance' . $e, 500);
		exit();
	}
}



// function _check_email_exists(){

// }

// ##############################
define('USER_NAME_MIN', 2);
define('USER_NAME_MAX', 20);
function _validate_user_name()
{

	$error = 'user_name min ' . USER_NAME_MIN . ' max ' . USER_NAME_MAX;

	if (!isset($_POST['user_name'])) {
		throw new Exception($error, 400);
	}
	$_POST['user_name'] = trim($_POST['user_name']);

	if (strlen($_POST['user_name']) < USER_NAME_MIN) {
		throw new Exception($error, 400);
	}

	if (strlen($_POST['user_name']) > USER_NAME_MAX) {
		throw new Exception($error, 400);
	}
}

// #############################
// TODO: ADD REGEX
define('USER_ADDRESS_MIN', 5);
define('USER_ADDRESS_MAX', 50);
function _validate_user_address()
{

	$error = 'user_address min ' . USER_ADDRESS_MIN . ' max ' . USER_ADDRESS_MAX;

	if (!isset($_POST['user_address'])) {
		throw new Exception($error, 400);
	}

	$_POST['user_address'] = trim($_POST['user_address']);

	if (strlen($_POST['user_address']) < USER_ADDRESS_MIN) {
		throw new Exception($error, 400);
	}

	if (strlen($_POST['user_address']) > USER_ADDRESS_MAX) {
		throw new Exception($error, 400);
	}
}

// ##############################
define('USER_LAST_NAME_MIN', 2);
define('USER_LAST_NAME_MAX', 20);
function _validate_user_last_name()
{

	$error = 'user_last_name min ' . USER_LAST_NAME_MIN . ' max ' . USER_LAST_NAME_MAX;

	if (!isset($_POST['user_last_name'])) {
		throw new Exception($error, 400);
	}
	$_POST['user_last_name'] = trim($_POST['user_last_name']);

	if (strlen($_POST['user_last_name']) < USER_LAST_NAME_MIN) {
		throw new Exception($error, 400);
	}

	if (strlen($_POST['user_last_name']) > USER_LAST_NAME_MAX) {
		throw new Exception($error, 400);
	}
}

// ##############################
function _validate_user_email()
{
	$error = 'user_email invalid';
	if (!isset($_POST['user_email'])) {
		throw new Exception($error, 400);
	}
	$_POST['user_email'] = trim($_POST['user_email']);
	if (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
		throw new Exception($error, 400);
	}
}

// ##############################
define('USER_PASSWORD_MIN', 6);
define('USER_PASSWORD_MAX', 50);

function _validate_user_password()
{

	$error = 'user_password min ' . USER_PASSWORD_MIN . ' max ' . USER_PASSWORD_MAX;

	if (!isset($_POST['user_password'])) {
		throw new Exception($error, 400);
	}
	$_POST['user_password'] = trim($_POST['user_password']);

	if (strlen($_POST['user_password']) < USER_PASSWORD_MIN) {
		throw new Exception($error, 400);
	}

	if (strlen($_POST['user_password']) > USER_PASSWORD_MAX) {
		throw new Exception($error, 400);
	}
}

// ##############################
function _validate_user_confirm_password()
{
	$error = 'user_confirm_password must match the user_password';
	if (!isset($_POST['user_confirm_password'])) {
		throw new Exception($error, 400);
	}
	$_POST['user_confirm_password'] = trim($_POST['user_confirm_password']);
	if ($_POST['user_password'] != $_POST['user_confirm_password']) {
		throw new Exception($error, 400);
	}
}

// ##############################
define('IMAGE_MIME_TYPES', array(
	'image/jpeg',
	'image/png',
));
define('IMAGE_EXTENSIONS', array(
	'jpg', 'jpeg', 'png'
));

function _validate_user_profile_picture()
{
	if (!isset($_FILES['user_profile_picture'])) {
		throw new Exception('Error handling image.', 500);
	}

	// Empty input field is still valid
	if ($_FILES['user_profile_picture']['size'] == 0) {
		// nothing
	} else {
		// Ensure fileupload was done via HTTP POST
		if (!is_uploaded_file($_FILES['user_profile_picture']['tmp_name'])) {
			throw new Exception('Invalid file upload method.', 400);
		}

		$upload_status = $_FILES['user_profile_picture']['error'];

		if ($upload_status !== UPLOAD_ERR_OK) {
			switch ($upload_status) {
				case UPLOAD_ERR_FORM_SIZE:
					// this value can be tampered with in the form. therefore we also check for on the server-side
					throw new Exception('File size is too large, must be below 2mb.', 400);
				case UPLOAD_ERR_PARTIAL:
					throw new Exception('File upload could not complete.', 400);
				case UPLOAD_ERR_NO_TMP_DIR:
					// C:\xampp\tmp
					throw new Exception('Missing temporary folder.', 500);
				case UPLOAD_ERR_CANT_WRITE:
					throw new Exception('Failed to write file.', 500);
				case UPLOAD_ERR_EXTENSION:
					// php extensions/settings/alike
					throw new Exception('File upload stopped by extension.', 400);
				default:
					throw new Exception('Error uploading image.', 500);
			}
		}

		$file_type = $_FILES['user_profile_picture']['type'];
		$file_size = $_FILES['user_profile_picture']['size'];
		$max_file_size = 2097152;
		$image_info = getimagesize($_FILES['user_profile_picture']['tmp_name']);
		$safe_filename = preg_replace('/[^a-zA-Z0-9-_\.]/', '', basename($_FILES['user_profile_picture']['name']));
		$file_extension = pathinfo($safe_filename, PATHINFO_EXTENSION);

		// Server-side size check
		if ($file_size > $max_file_size) {
			throw new Exception('File size is too large, must be below 2mb.', 400);
		}

		// File extension check
		if (!in_array(strtolower($file_extension), IMAGE_EXTENSIONS)) {
			throw new Exception('Invalid file extension. Only JPEG, JPG and PNG files are allowed.', 400);
		}

		if (!in_array($file_type, IMAGE_MIME_TYPES)) {
			throw new Exception('Invalid file type. Only JPEG, JPG and PNG files are allowed.', 400);
		}

		if ($image_info === false) {
			throw new Exception('Invalid image file.', 400);
		}

		if ($image_info[2] !== IMAGETYPE_JPEG && $image_info[2] !== IMAGETYPE_PNG) {
			throw new Exception('Invalid image format. Only JPEG, JPG and PNG files are allowed.', 400);
		}
	}
}


function _generate_user_profile_picture()
{
	$file = $_FILES['user_profile_picture'];

	if ($_FILES['user_profile_picture']['size'] == 0) {
		return false;
	}

	$target_dir = "../uploads/";

	if (!is_writable($target_dir)) {
		throw new Exception('Upload directory is not writable.');
	}
	// User name (has already been validated)
	$user_name = $_POST['user_name'];

	// Generate current UNIX timestamp
	$timestamp = time();

	// Generate a random string
	$uniqid = uniqid();

	$file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);

	// Combine the elements to create the unique filename
	$file_name = $user_name . '_' . $timestamp . '_' . $uniqid . '.' . $file_extension;

	$target_file = $target_dir . $file_name;

	while (file_exists($target_file)) {
		// Generate a new unique identifier
		$uniqid = uniqid();

		// Update the filename with the new identifier
		$file_name = $user_name . '_' . $timestamp . '_' . $uniqid . '.' . $file_extension;

		$target_file = $target_dir . $file_name;
	}

	if (move_uploaded_file($file['tmp_name'], $target_file)) {
		return $file_name; // Return the path to the moved file
	}

	throw new Exception('Failed to move uploaded file.', 500);
}

function _remove_user_profile_picture($file_name)
{
	$target_dir = __DIR__ . "/uploads/";
	$file_path = $target_dir . $file_name;

	if (!file_exists($file_path)) {
		throw new Exception('Could not find file.:' . $file_path, 500);
	}

	if (!unlink($file_path)) {
		throw new Exception('Could not delete file.', 500);
	}
}

// function _validate_user_current_password(){
//   $error = 'error'

// }


// ##############################
function _is_admin()
{
	return (!isset($_SESSION['user']) || $_SESSION['user']['user_role_fk'] != 1) ? false : true;
}


// #############################
// check role
// is this secure??
function _check_role($role)
{
	return (isset($_SESSION['user'])) && $_SESSION['user']['user_role_fk'] == $role;
}

function _is_allowed($blocked_check, $deleted_check)
{
	if ($blocked_check == 1 || $deleted_check > 0) {
		return false;
	} else {
		return true;
	}
}

// ##############################
function out($text)
{
	echo htmlspecialchars($text);
}
