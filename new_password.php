<?php require_once 'include/db.php'; ?>
<?php require_once 'include/functions.php'; ?>
<?php  $current_page="new_password"; ?>
<?php include_once('include/header.php');?>
    <div class="container">
        <div class="content">
            <h2 class="heading">New Password</h2>

              <?php
                  if(isset($_GET['eid']) AND isset($_GET['token']) AND isset($_GET['exd'])){
                    $email = urldecode(base64_decode($_GET['eid']));  //decrypting email from confirmation link
                    $expire_date = urldecode(base64_decode($_GET['exd']));
                    $validation_key = urldecode(base64_decode($_GET['token']));

                    date_default_timezone_set('Asia/Kolkata');
                    $current_date = date("Y-m-d H:i:s");

                    if($current_date >= $expire_date){
                      echo "<div class='notification'>Link expired</div>";
                      $check = true;  //used to disable input fields if link is expired
                    }else {

                      if(isset($_POST['submit'])){
                        $password = escape($_POST['new-password']);
                        $confirm_password = escape($_POST['confirm-new-password']);

                        //password validation
                        if ($password==$confirm_password) {
                            $pattern_pass = "/^.*(?=.{4,30})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$/"; // . indicates all the characters. * indicates at least 0 times pattern should be repeated
                            if (!preg_match($pattern_pass, $password)) {
                                $errpass = "Must be at least 4 characters long, 1 upper case, 1 lower case and 1 number";
                            }
                        } else {
                            $errpass ="Password doesn't matched";
                        }
                        if(!isset($errpass)){
                        $query = "SELECT * FROM user_details WHERE email = '$email' AND validation_key = '$validation_key' AND is_active=1";
                        $query_run = mysqli_query($connection, $query);

                        if(!$query_run){
                          die("Unable to connect to database".mysqli_error($connection));
                        }

                        if(mysqli_num_rows($query_run)==1){

                            $password_hash = password_hash($password, PASSWORD_BCRYPT, ['cost'=>10]);

                            //update password
                            $query1 = "UPDATE user_details SET password = '$password_hash' WHERE validation_key = '$validation_key' AND email='$email' AND is_active=1";
                            $query_run1 = mysqli_query($connection, $query1);
                            if(!$query_run1){
                              die("Connection to database failed! ".mysqli_error($connection));
                            }else {
                              $query2 = "UPDATE user_details SET validation_key=0 WHERE validation_key='$validation_key' AND email = '$email' AND is_active=1";
                              $query_run2 = mysqli_query($connection, $query2);
                              if(!$query_run2){
                                die("Unable to connect to database".mysqli_error());
                              }
                              echo "<div class='notification'>Password has successfully updated.</div>";
                              header("Refresh:3;url=login.php");
                            }
                          }else echo "<div class='notification'>Invalid link</div>";
                        }
                      }

}
                  }else {
                    echo "<div class='notification'>Something went wrong:(</div>";
                  }
              ?>
            <!-- <div class='notification'>Password updated successfully. <a href='login.php'>login now</a></div> -->
            <form action="" method="POST">
                <div class="input-box">
                    <input type="password" class="input-control" placeholder="New password" name="new-password" <?php echo isset($check)? "disabled": ""?> required>
                    <?php echo isset($errpass)?"<span class='error'>$errpass</span>" : ""; ?>
                </div>
                <div class="input-box">
                    <input type="password" class="input-control" placeholder="Confirm new password" name="confirm-new-password" <?php echo isset($check)? "disabled": ""?> required>
                </div>
                <div class="input-box">
                    <input type="submit" class="input-submit" value="SUBMIT" name="submit" >
                </div>
            </form>

        </div>
    </div>
<?php include_once('include/footer.php'); ?>
