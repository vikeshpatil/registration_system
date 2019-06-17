<?php
    function escape($string){
        global $connection;
        return mysqli_real_escape_string($connection, $string);
    }

    function getToken($len){
      $random_string = md5(uniqid(mt_rand(),true));
      $base64_encoded = base64_encode($random_string);
      $modified_base64_encoded = str_replace(array('+','='), array('', ''), $base64_encoded);
      $token = substr($modified_base64_encoded, 0, $len);
      return $token;
    }

    function selectbytoken($token){
      global $connection;
      $query = "SELECT username FROM remember_me WHERE selector = '$token'";
      $query_run = mysqli_query($connection, $query);
      if(!$query_run){
        die("Unable to connect to database".mysqli_error($connection));
      }
      $result = mysqli_fetch_assoc($query_run);
      $username = $result['username'];
      $query1 = "SELECT * FROM user_details WHERE username = '$username'";
      $query_run1 = mysqli_query($connection, $query1);
      if(!$query_run1){
        die("Unable to connect to database".mysqli_error($connection));
      }
      $result1 = mysqli_fetch_assoc($query_run1);
      return $result1['first_name']." ".$result1['last_name'];
    }

    //checking if user is logged in with remember_me coockie
    function isAlreadyloggedin(){
      global $connection;
      date_default_timezone_set('Asia/Kolkata');
      $current_date = date("Y-m-d H:i:s");
      if(isset($_COOKIE['remember_me'])){
          $selector = escape(base64_decode($_COOKIE['remember_me']));
          $query= "SELECT * From remember_me WHERE selector = '$selector' AND is_expired = 0";
          $query_run = mysqli_query($connection, $query);
          if(!$query_run){
            die("Unable to connect to database" . mysqli_error($connection));
          }

          $result = mysqli_fetch_assoc($query_run);
          if(mysqli_num_rows($query_run)==1){
            $expire_date = $result['expire_date'];
            if($expire_date>=$current_date){
              $name = selectbytoken($selector);
              $_SESSION['name'] = $name;
              return true;
            }
          }
      }
    }
?>
