<?php
/**
 * * @file
 * php version 7.4.33
 * 
 * Registration Page for Api To-Do-List
 * 
 * @category Php_API
 * @package  Src-Folder
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.tasks/register.com
 */
// NEED TO ADD session_start()
require __DIR__ . "/vendor/autoload.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // NEED TO ADD VALIDATION AND INPUT ERROR CHECK //
    $name            = $_POST["name"];
    $username        = $_POST["username"];
    $password        = $_POST["password"];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $api_key         = bin2hex(random_bytes(16)); 

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $database = new Database(
        $_ENV['DB_HOST'], 
        $_ENV['DB_USER'], 
        $_ENV['DB_PASS'], 
        $_ENV['DB_NAME']
    );
    
    $users    = new UsersGateway($database);
    $new_user = $users->createUser($name, $username, $hashed_password, $api_key); 

    if ($new_user !== false) {
        $user_entered = $new_user;
        header("location: /api_framework_php/register.php");
        exit;
    } else {
        echo "Something went wrong";
        exit;
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
  <title>Registration Page</title>
</head>
<body>

  <main class="container">

    <h1>Register</h1>
    <!-- NEED TO ADD SUCCESS AND UNSUCCESFUL MESSAGE -->
    <form method="post">

      <label for="name"> Name 
        <input type="text" name="name" id="name"> 
      </label>
      <!-- NEED TO ADD ERRO MESSAGE IF VALIDATION FAILS -->
      <label for="username"> Username 
        <input type="text" name="username" id="username"> 
      </label>
      <!-- NEED TO ADD ERRO MESSAGE IF VALIDATION FAILS -->
      <label for="password"> Password 
        <input type="password" name="password" id="password"> 
      </label>
      <!-- NEED TO ADD ERRO MESSAGE IF VALIDATION FAILS -->
      
      <button>Register</button>
    
    </form>

  </main>
  
</body>
</html>