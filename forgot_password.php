<?php $current_page = "forgot_password"; ?>
<?php include_once('include/header.php'); ?>
    <div class="container">
        <div class="content">
            <h2 class="heading">Password Recovery</h2>
            <?php

              if(isset($_POST['password_recovery'])){
                    $username = escape($_POST['user_name']);
                    $email = escape($_POST['user_email']);

                    $query = "SELECT * FROM user_details WHERE username = '$username' AND email = '$email'";
                    $query_run = mysqli_query($connection, $query);

                    if(!$query_run){
                      die("Connection to database failed".mysqli_error($connection));
                    }
                    $row_num = mysqli_num_rows($query_run);
                    if($row_num==1){
                      if(!isset($_COOKIE['password_recovery'])){

                      // password_recovery email
                      date_default_timezone_set('Asia/Kolkata');
                      $token = getToken(32);
                      $encode_token = base64_encode(urlencode($token));
                      $encrypted_email = base64_encode(urlencode($_POST['user_email']));
                      $expire_date = date("Y-m-d H:i:s", time() + 60 * 5);
                      $expire_date = base64_encode(urlencode($expire_date));

                      $query = "UPDATE user_details SET validation_key ='$token' WHERE username='$username' AND email='$email' AND is_active=1";
                      $query_run = mysqli_query($connection, $query);
                      if(!$query_run){
                        die("Unable to connect to database".mysqli_error());
                      }else{
                        $mail->Subject="Password recovery request";   //recipient
                        $mail->addAddress($email);
                        $mail->Body="Follow the link to reset password.<br>
                                      <a href='localhost/projects/registration/new_password.php?eid={$encrypted_email}&token={$encode_token}&exd={$expire_date}'>click here</a> to reset password.
                                      <br> This link is valid for only 5 minutes.";
                        if($mail->send()){
                          setcookie('password_recovery', getToken(16), time() + 60 * 5, '', '', '', true);
                          echo "<div class='notification'>Check your email for password reset link.</div>";
                        }
                      }
                    }else{
                        echo "<div class='notification'>You must wait for at least 5 minutes for next link.</div>";
                  }
                }else echo "<div class='notification'>Sorry! user not found.</div>";
              }

             ?>


            <form action="" method="POST">
                <div class="input-box">
                    <input type="text" class="input-control" placeholder="Username" name="user_name" required>
                </div>
                <div class="input-box">
                    <input type="email" class="input-control" placeholder="Email address" name="user_email" required>
                </div>
                <div class="input-box">
                    <input type="submit" class="input-submit" value="RECOVER PASSWORD" name="password_recovery" required>
                </div>
            </form>
        </div>
    </div>

<?php include_once('include/footer.php'); ?>
