<?php

  ob_start();
  session_start();
 ?>
 <?php
 require_once 'include/functions.php';
 require_once 'include/db.php';
  ?>
<?php $current_page = "index"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Index</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body class='front-end'>
<?php
  if(isset($_SESSION['login'])){
    echo "<div>You are logged in <a href='logout.php'>Logout</a> </div>";
  }elseif(isAlreadyloggedin()){
    echo "<div>Welcome back {$_SESSION['name']} <a href='logout.php'>Logout</a> </div>";
  }

  else {
     echo "<div>You are not logged in <a href='login.php'>Login now</a> </div>";
  }

 ?>

<?php include_once('include/footer.php'); ?>
