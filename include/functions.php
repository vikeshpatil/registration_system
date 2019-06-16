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
?>
