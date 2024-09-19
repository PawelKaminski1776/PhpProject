<?php
try { 
$pdo = new PDO('mysql:host=localhost;dbname=BowlingSYS; charset=utf8', 'root', ''); 
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql="SELECT count(*) FROM lane WHERE laneid=:lid";

$result = $pdo->prepare($sql);
$result->bindValue(':lid', $_GET['laneid']); 
$result->execute();
if($result->fetchColumn() > 0) 
{
    $sql = 'SELECT * FROM lane where laneid = :lid';
    $result = $pdo->prepare($sql);
    $result->bindValue(':lid', $_GET['laneid']); 
    $result->execute();

    $row = $result->fetch() ;
    $id = $row['laneid'];
	  $status= $row['status'];
   
}

else {
      print "No rows matched the query. try again click<a href='selectupdate.php'> here</a> to go back";
    }} 
catch (PDOException $e) { 
$output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine(); 
}



include 'updatedetails.html';
?>


