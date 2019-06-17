<?php
require_once 'include/db.php';
require_once 'include/functions.php';
    ob_start();
    session_start();

if(isset($_COOKIE['remember_me'])){
  global $connection;
  $selector = escape(base64_decode($_COOKIE['remember_me']));
  $query = "UPDATE remember_me SET is_expired = -1 WHERE selector ='$selector' AND is_expired = 0";
  $query_run = mysqli_query($connection, $query);
  if(!$query_run){
    die("Connection to database failed".mysqli_error($connection));
  }
  setcookie('remember_me', '', time() - 60*60);
}
    if(isset($_SESSION['login'])){
      session_destroy();
      unset($_SESSION['login']);
      unset($_SESSION['name']);
      header("Location:login.php");
    }
    header("Location: login.php");
?>
