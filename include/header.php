<?php ob_start(); ?>
<?php require_once('include/functions.php');?>
<?php require_once('include/db.php'); ?>
<?php if(isset($_SESSION['login'])){ header("Location: index.php"); } ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $current_page;?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
  if(isAlreadyloggedin()){
    header("Location: index.php");
    exist;
  }
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

 $mail = new PHPMailer();
 $mail->isSMTP(true);
 $mail->SMTPAuth = true;
 $mail->Host = "smtp.gmail.com";
 $mail->Port = "465";
 $mail->SMTPSecure = "ssl";
 $mail->Username = "";  //sender email id
 $mail->Password = "";  //sender email id's password
 $mail->From = "";  //sender email id
 $mail->FromName = "";  //sender name
 $mail->addReplyto("no-reply@example.com", "no-reply");
 $mail->isHTML(true);

 ?>
