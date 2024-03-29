<?php $current_page = "Sign Up";?>
<?php require_once('include/header.php'); ?>

    <div class="container">
        <div class="content">
            <h2 class="heading">Sign Up</h2>

<?php


  //google recaptcha
  $public_key = "";
  $private_key = "";
  $url = "https://www.google.com/recaptcha/api/siteverify";

  if (isset($_POST['sign-up'])) {

      //Google recaptcha
      $response_key = $_POST['g-recaptcha-response'];
      //https://www.google.com/recaptcha/site/reverify?secret=$private_key&response=$response_key&remoteip=currentScriptAddress
      $response=file_get_contents($url."?secret=".$private_key."&response=".$response_key."&remoteip=".$_SERVER['REMOTE_ADDR']);
      $response = json_decode($response);

        if(!($response->success == 1)){
            $errCaptcha = "Wrong captcha";
        }

      //grabbing user entered values
      $firstname = escape($_POST['first_name']);
      $lastname = escape($_POST['last_name']);
      $username = escape($_POST['user_name']);
      $email = escape($_POST['user_email']);
      $password = escape($_POST['user_password']);
      $confirm_password = escape($_POST['user_confirm_password']);

      //first name validation
        $pattern_fn = "/^[a-zA-Z ]{3,12}$/";// ^ indicates start of regular expression and $ indiacates end of regular expression
        if (!preg_match($pattern_fn, $firstname)) {
            $errFn = "Must be at lest 3 character long, letter and space allowed";
        }

      //last name validation
       $pattern_fn = "/^[a-zA-Z ]{3,12}$/"; //{3,12} specifies the limit of characters
       if (!preg_match($pattern_fn, $lastname)) {
           $errLn = "Must be at lest 3 character long, letter and space allowed";
       }

      //username validation
      $pattern_fn = "/^[a-zA-Z0-9_]{3,12}$/";
      if (!preg_match($pattern_fn, $username)) {
          $errUn = "Must be at lest 3 character long, letter, numbers and underscore allowed";
      }else {
        $query = "SELECT * FROM user_details WHERE username ='$username'";
        $query_run_username = mysqli_query($connection, $query);
        if(!$query_run_username){
          die("Connection to database failed" . mysqli_error($connection));
        }else {
          $row_num = mysqli_num_rows($query_run_username);
          if($row_num == 1){
            $errUn = "Username has already taken.";
          }
        }

      }

      //email validation
      $pattern_e = "/^([a-z0-9_\+\-]+)(\.[a-z0-9\+\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/i"; /*+ specifies at least once and * specifies at least 0 or more.
                                                                                        \ is to escape the character. i specifies independent of the case*/
      if (!preg_match($pattern_e, $email)) {
          $errE = "Invalid email format!";
      }else {     //check if email is already present in database
        $query = "SELECT * FROM user_details WHERE email ='$email'";
        $query_run_email = mysqli_query($connection, $query);
        if(!$query_run_email){
          die("Connection to database failed" . mysqli_error($connection));
        }else {
          $row_num = mysqli_num_rows($query_run_email);
          if($row_num >= 1){
            $errE = "Email has already taken.";
          }
        }
      }

      //password validation
      if ($password==$confirm_password) {
          $pattern_pass = "/^.*(?=.{4,30})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$/"; // . indicates all the characters. * indicates at least 0 times pattern should be repeated
          if (!preg_match($pattern_pass, $password)) {
              $errpass = "Must be at least 4 characters long, 1 upper case, 1 lower case and 1 number";
          }
      } else {
          $errpass ="Password doesn't matched";
      }

      //adding data to database
        if ( !isset($errFn) && !isset($errLn) && !isset($errUn) && !isset($errE) && !isset($errpass)) {

            date_default_timezone_set("Asia/Kolkata");
            $date = date("Y-m-d H:i:s");

            //email conformation
                    $token = getToken(32);
                    $encrypted_email = base64_encode(urlencode($_POST['user_email']));

                    $expire_date = date("Y-m-d H:i:s", time() + 30);
                    $expire_date = base64_encode(urlencode($expire_date));

                    $mail->Subject="Verify your email";   //recipient
                    $mail->addAddress($email);
                    $mail->Body=" Hello {$firstname} &nbsp;<br> Thanks for registration.<br>
                                  <a href='localhost/projects/registration/activation.php?eid={$encrypted_email}&token={$token}&&exd={$expire_date}'>click here</a> to verify your email.
                                    <br> This is link is valid for only 20 minutes.";

                    if($mail->send()){
                      $hash = password_hash($password, PASSWORD_BCRYPT, ['cost'=>10]);    //BCRYPT is a password hashing algorithm. the third argument specifies that the algorithm will run 10 times
                        $query = "INSERT INTO user_details (first_name, last_name, username, email, password, validation_key, registration_date) VALUES('$firstname', '$lastname', '$username', '$email', '$hash', '$token', '$date')";

                        $query_run = mysqli_query($connection, $query);
                        if(!$query_run){
                            die("Query failed".mysqli_error($connection));
                        }else{
                          echo "<div class='notification'>Sign up successful. Check email for activation link.</div>";
                          //resetting values entered by user after successful sign up
                          unset($firstname);
                          unset($lastname);
                          unset($username);
                          unset($email);
                        }

                    }else echo "<div class='notification'>Something went wrong:(</div>";

    }
  }
?>

            <form action="sign_up.php" method="POST">
                <div class="input-box">
                    <input type="text" class="input-control" placeholder="First name" value="<?php echo isset($firstname)? $firstname: ""?>" name="first_name" autocomplete="off" >
                    <?php echo isset($errFn)? "<span class='error'>$errFn</span>": "";  ?>
                </div>
                <div class="input-box">
                    <input type="text" class="input-control" placeholder="Last name" value="<?php echo isset($lastname)? $lastname: ""?>" name="last_name" autocomplete="off" >
                    <?php echo isset($errLn)? "<span class='error'>$errLn</span>": "";  ?>
                </div>
                <div class="input-box">
                    <input type="text" class="input-control" placeholder="Username" value="<?php echo isset($username)? $username: ""?>" name="user_name" autocomplete="off" >
                    <?php echo isset($errUn)? "<span class='error'>$errUn</span>": "";  ?>
                </div>
                <div class="input-box">
                    <input type="email" class="input-control" placeholder="Email address" value="<?php echo isset($email)? $email: ""?>" name="user_email" autocomplete="off" >
                    <?php echo isset($errE)? "<span class='error'>$errE</span>": "";  ?>
                </div>
                <div class="input-box">
                    <input type="password" class="input-control" placeholder="Enter password" name="user_password" autocomplete="off" >
                    <?php echo isset($errpass)? "<span class='error'>$errpass</span>" : ""; ?>
                </div>
                <div class="input-box">
                    <input type="password" class="input-control" placeholder="Confirm password" name="user_confirm_password" autocomplete="off" >

                </div>
                <div class="g-recaptcha" data-sitekey="<?php echo $public_key; ?>"></div>
                <?php echo isset($errCaptcha)? "<span class='error'>$errCaptcha</span>" : ""; ?>
                <div class="input-box">
                    <input type="submit" class="input-submit" value="SIGN UP" name="sign-up">
                </div>
                <div class="sign-up-cta"><span>Already have an account?</span> <a href="login.php">Login here</a></div>
            </form>

        </div>
    </div>
<?php include_once('include/footer.php'); ?>
