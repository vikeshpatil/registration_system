<? $current_page = "Sign Up";?>
<? include_once('include/header.php'); ?>
  
    <div class="container">
        <div class="content">
            <h2 class="heading">Sign Up</h2>

<?
  
  if(isset($_POST['sign-up'])){
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
       }

       //email validation
       $pattern_e = "/^([a-z0-9_\+\-]+)(\.[a-z0-9\+\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/i"; /*+ specifies at least once and * specifies at least 0 or more. 
                                                                                        \ is to escape the character. i specifies independent of the case*/
       if(!preg_match($pattern_e, $email)){
           $errE = "Invalid email format!";
       }
       //password validation
      if($password==$confirm_password){
          $error_pass = false;
      }else{
          $error_pass ="Password doesn't matched";
      }

  }
     
?>

            <!-- <div class='notification'>Sign up successful. Check your email for activation link</div> -->
            <form action="sign_up.php" method="POST">
                <div class="input-box">
                    <input type="text" class="input-control" placeholder="First name" name="first_name" autocomplete="off" >
                    <? echo isset($errFn)? "<span class='error'>$errFn</span>": "";  ?>
                </div>
                <div class="input-box">
                    <input type="text" class="input-control" placeholder="Last name" name="last_name" autocomplete="off" >
                    <? echo isset($errLn)? "<span class='error'>$errLn</span>": "";  ?>
                </div>
                <div class="input-box">
                    <input type="text" class="input-control" placeholder="Username" name="user_name" autocomplete="off" >
                    <? echo isset($errUn)? "<span class='error'>$errUn</span>": "";  ?>
                </div>
                <div class="input-box">
                    <input type="email" class="input-control" placeholder="Email address" name="user_email" autocomplete="off" >
                    <? echo isset($errE)? "<span class='error'>$errE</span>": "";  ?>
                </div>
                <div class="input-box">
                    <input type="password" class="input-control" placeholder="Enter password" name="user_password" autocomplete="off" >
                    <? global $error_pass; echo ($error_pass)? "<span class='error'>$error_pass</span>" : ""; ?>
                </div>
                <div class="input-box">
                    <input type="password" class="input-control" placeholder="Confirm password" name="user_confirm_password" autocomplete="off" >
                    
                </div>
                <div class="input-box">
                    <input type="submit" class="input-submit" value="SIGN UP" name="sign-up">
                </div>
                <div class="sign-up-cta"><span>Already have an account?</span> <a href="login.php">Login here</a></div>
            </form>

        </div>
    </div>
<? include_once('include/footer.php'); ?>