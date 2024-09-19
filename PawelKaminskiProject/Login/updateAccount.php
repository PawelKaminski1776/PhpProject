<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not
    header('Location: login.php');
    exit();
}

try { 
$pdo = new PDO('mysql:host=localhost;dbname=BowlingSYS; charset=utf8', 'root', ''); 
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql="SELECT forename, surname, email, password, telephone FROM customer WHERE custid= :username";

$result = $pdo->prepare($sql);
$result->bindValue(':username',  $_SESSION['username']); 
$result->execute();
if($result->fetchColumn() > 0) 
{
    $sql = 'SELECT * FROM customer where custid = :username';
    $result = $pdo->prepare($sql);
    $result->bindValue(':username', $_SESSION['username']); 
    $result->execute();

    $row = $result->fetch() ;
	$forename= $row['forename'];
	$surname=$row['surname'];
    $password=$row['password'];
    $telephone=$row['telephone'];
	$email=$row['email'];
}

else {
      print "No rows matched the query. try again click<a href='selectupdate.php'> here</a> to go back";
    }} 
catch (PDOException $e) { 
$output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine(); 
}


include 'UpdateAccount.html';
include 'footer.html';

?>