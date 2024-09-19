<?php
session_start();


try { 
$pdo = new PDO('mysql:host=localhost;dbname=BowlingSYS; charset=utf8', 'root', ''); 
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = 'DELETE FROM customer WHERE custid = :username';
$result = $pdo->prepare($sql);
$result->bindValue(':username', $_SESSION['username']); 
$result->execute();
     

header('Location: Login.html');
                                                                        
} 
catch (PDOException $e) { 

if ($e->getCode() == 23000) {
          header('Location: Account.php');
     }

}
?>