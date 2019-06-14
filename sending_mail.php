<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

 $mail = new PHPMailer();
 $mail->SMTPDebug=2;
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

//recipient
 $mail->addAddress("rajp8340@gmail.com");
 $mail->isHTML(true);
 $mail->Subject="sending from local host";
 $mail->Body="<div> This message is sent from the <i>local host</i> </div>";

 if($mail->send()){
     echo "mail sent";
 }else echo "unable to sent the mail";
 
 ?>
