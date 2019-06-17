<?php     session_start(); ?>
<?php $current_page = "login";?>
<?php include_once('include/header.php'); ?>
    <div class="container">
        <div class="content">
            <h2 class="heading">Login</h2>

            <?php

              //google recaptcha
              $public_key = "6LfZqKgUAAAAANcIkkAEBCkjDStTAXIHiAe62Stw";
              $private_key = "6LfZqKgUAAAAAPnh31kDHdXjbEseb8Q28ERf-nxR";
              $url = "https://www.google.com/recaptcha/api/siteverify";


              if(isset($_POST['resend'])){
                if(!isset($_COOKIE['expire_time'])){
                $username= $_POST['user_name'];
                $email = $_POST['user_email'];

                // email conformation
                date_default_timezone_set('Asia/Kolkata');
                $token = getToken(32);
                $encrypted_email = base64_encode(urlencode($_POST['user_email']));
                $expire_date = date("Y-m-d H:i:s", time() + 60 * 5);
                $expire_date = base64_encode(urlencode($expire_date));

                $query = "UPDATE user_details SET validation_key ='$token' WHERE username='$username' AND email='$email'";
                $query_run = mysqli_query($connection, $query);
                if(!$query_run){
                  die("Unable to connect to database".mysqli_error());
                }else{
                  $mail->Subject="Verify your email";   //recipient
                  $mail->addAddress($email);
                  $mail->Body="  Follow the link to verify.<br>
                                <a href='localhost/projects/registration/activation.php?eid={$encrypted_email}&token={$token}&exd={$expire_date}'>click here</a> to verify your email.
                                  <br> This link is valid for only 5 minutes.";
                  if($mail->send()){
                    setcookie('expire_time', getToken(16), time() + 60 * 5, '', '', '', true);
                    echo "<div class='notification'>Check your email for activation</div>";
                  }
                }
              }else{
                  echo "<div class='notification'>You must wait for at least 5 minutes for next link.</div>";
            }
              }
              $isAuthenticated = false;
              if(isset($_POST['login'])){
                //Google recaptcha
                $response_key = $_POST['g-recaptcha-response'];
                //https://www.google.com/recaptcha/site/reverify?secret=$private_key&response=$response_key&remoteip=currentScriptAddress
                $response=file_get_contents($url."?secret=".$private_key."&response=".$response_key."&remoteip=".$_SERVER['REMOTE_ADDR']);
                $response = json_decode($response);

                  if(!($response->success == 1)){
                      $errCaptcha = "Wrong captcha";
                  }


                $username = escape($_POST['user_name']);
                $email = escape($_POST['user_email']);
                $password = escape($_POST['user_password']);

                $query = "SELECT * FROM user_details WHERE username = '$username' AND email = '$email'";
                $query_run = mysqli_query($connection, $query);
                if(!$query_run){
                  die("Unable to connect to database".mysqli_error($connection));
                }
                $result = mysqli_fetch_assoc($query_run);
                if(password_verify($password, $result['password'])){
                  if($result['is_active'] == 1){
                    if(!isset($errCaptcha)){
                      $isAuthenticated =true;
                      echo "<div class='notification'>Logged In Successfull</div>";
                      //resetting fields
                      unset($username);
                      unset($email);
                      unset($password);
                  }
                }else{
                    if(!isset($errCaptcha)){
                  echo "<div class='notification'>You are not verified user. <form method='POST'><input type='text' value='{$username}' name='user_name' hidden><input type='text' value='{$email}' name='user_email' hidden><button class='resend' name='resend'>Click here to resend</button></form></div>";
                }
              }

                }else {
                  echo "<div class='notification'>Invalid username or email or password</div> ";
                }
              }

              if($isAuthenticated){

                if(!empty($_POST['remember-me'])){

                  $selector = getToken(32);
                  $encoded_selector = base64_encode($selector);
                  setcookie('remember_me', $encoded_selector, time()+ 60*5, '','','',true); //remember_me for 5 minute

                  date_default_timezone_set('Asia/Kolkata');
                  $expire_date_remember_me = date("Y-m-d H:i:s", time()+60*5);
                  $username = escape($_POST['user_name']);
                  $query = "INSERT Into remember_me(username, selector, expire_date, is_expired) VALUES('$username', '$selector', '$expire_date_remember_me', 0)";
                  $query_run = mysqli_query($connection, $query);
                  if(!$query_run){
                    die("Connection to database failed!".mysqli_error());
                  }
                }
                $_SESSION['login'] ='success';
                header("Refresh:1;url=index.php");
              }

             ?>
            <!-- <div class='notification'>Logged In Successfull</div> -->
            <form action="login.php" method="POST">
                <div class="input-box">
                    <input type="text" class="input-control" placeholder="Username" value="<?php echo isset($username)?"{$username}":"";?>" name="user_name" required>
                </div>
                <div class="input-box">
                    <input type="email" class="input-control" placeholder="Email address" value="<?php echo isset($email)?"{$email}":"";?>" name="user_email" required>
                </div>
                <div class="input-box">
                    <input type="password" class="input-control" placeholder="Enter password" name="user_password" required>
                </div>
                <div class="input-box rm-box">
                    <div>
                        <input type="checkbox" id="remember-me" class="remember-me" name="remember-me">
                        <label for="remember-me">Remember me</label>
                    </div>
                    <a href="forgot_password.php" class="forgot-password">Forgot password?</a>
                </div>

                <div class="g-recaptcha" data-sitekey="<?php echo $public_key; ?>"></div>
                <?php echo isset($errCaptcha)? "<span class='error'>$errCaptcha</span>" : ""; ?>

                <div class="input-box">
                    <input type="submit" class="input-submit" value="LOGIN" name="login">
                </div>
                <div class="login-cta"><span>Don't have an account?</span> <a href="sign_up.php">Sign up here</a></div>
            </form>

        </div>
    </div>
<?php include_once('include/footer.php');?>
