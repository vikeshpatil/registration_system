<?php require_once('include/functions.php');?>
<?php require_once('include/db.php'); ?>
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
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

 $mail = new PHPMailer();
 $mail->isSMTP(true);
 $mail->SMTPAuth = true;
 $mail->Host = "smtp.gmail.com";
 $mail->Port = "465";
 $mail->SMTPSecure = "ssl";
 $mail->Username = "vikesh.patil8340@gmail.com";
 $mail->Password = "Detroit&95";
 $mail->From = "vikesh.patil8340@gmail.com";
 $mail->FromName = "Vikesh Patil";
 $mail->addReplyto("no-reply@vikesh.com", "no-reply");
 $mail->isHTML(true);

 ?>
