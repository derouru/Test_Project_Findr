<?php
// php debugging block

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
?>

<?php
session_start();

if ( isset($_GET["id"]) ) {             // if id of the item exists
    $item_id = $_GET["id"];             // read id

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "findrdatabase";

    // creating connection
    $connection = new mysqli($servername, $username, $password, $database);

    // delete item with specified ID 
    $sql = "DELETE FROM lostfounditems WHERE item_id=$item_id";
    $connection->query($sql);
}

// redirecting user to index file (list of items), and exit execution of this file
header("location: index.php");
exit;

?>
