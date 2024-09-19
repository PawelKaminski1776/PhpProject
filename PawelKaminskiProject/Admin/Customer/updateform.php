<?php
include 'header.html';
try { 
$pdo = new PDO('mysql:host=localhost;dbname=BowlingSYS; charset=utf8', 'root', ''); 
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql="SELECT count(*) FROM customer WHERE custid=:cid";

$result = $pdo->prepare($sql);
$result->bindValue(':cid', $_GET['custid']); 
$result->execute();
if($result->fetchColumn() > 0) 
{
    $sql = 'SELECT * FROM customer where custid = :cid';
    $result = $pdo->prepare($sql);
    $result->bindValue(':cid', $_GET['custid']); 
    $result->execute();

    $row = $result->fetch() ;
    $id = $row['custid'];
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


include 'updatedetails.html';
?>


