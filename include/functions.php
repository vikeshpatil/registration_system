<?
    function escape($string){
        global $connection;
        return mysqli_real_escape_string($connection, $string);
    }
?>