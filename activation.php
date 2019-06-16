<?php
require_once 'include/db.php';
require_once 'include/functions.php';

  if(isset($_GET['eid']) AND isset($_GET['token']) AND isset($_GET['exd'])){
    $email=urldecode(base64_decode($_GET['eid']));  //decrypting email from confirmation link
    $validation_key = $_GET['token'];

    //Check if link is expired
    $expire_date = urldecode(base64_decode($_GET['exd']));

    date_default_timezone_set('Asia/Kolkata');
    $current_date = date('Y-m-d H:i:s');

    if($current_date >= $expire_date){
      echo 'Link expired!';
    }else {

      //check if already verified
      $query1 = "SELECT * FROM user_details where email='$email' AND validation_key='$validation_key' AND is_active = 1";
      $query1_run = mysqli_query($connection, $query1);
      if(!$query1_run){
        die("Connection to database failed!" . mysqli_error($connection));
      }

      $row_num = mysqli_num_rows($query1_run);  //if any user status of is_active as 1 the it will return the row

      if($row_num==1){
        echo "Email already verified.";
      }else {
        //Query
        $query = "UPDATE user_details SET is_active=1 where email='$email' AND validation_key = '$validation_key'";
        $query_run = mysqli_query($connection, $query);
        if(!$query_run){
          die("Connection to database failed!".mysqli_error($connection));
        }
        if($query_run){
          echo "<div><b>Email verified.</b></div>";
        }
      }

    }

}
 ?>
