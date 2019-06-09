<?

define("dbhost", "localhost");
define("username", "root");
define("password", "");
define("dbname", "registration");

@$connection = mysqli_connect(dbhost, username, password, dbname);

if(!$connection)
    die("Unable to connect the database.");
    ?>