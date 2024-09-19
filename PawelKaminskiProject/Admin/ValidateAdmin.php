<?php

include('AdminLogin.html');

if (isset($_POST['submitdetails'])) {
    $username = $_POST['ausername'];
    $password = $_POST['apassword'];
    if($username == "Admin123" && $password == "Admin123"){
        header('Location: AdminPage.html');
    }
    else {
        echo "<script>alert('Error: Incorrect admin details');</script>";
    }
}

?>