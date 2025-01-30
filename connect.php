<?php
$con=new mysqli('localhost', 'root', '','blinkbasket');
    
if ($con) {
    // echo 'Done';
} else {
    die(mysqli_error($con));
}

?>