<?php
try {
     $pdo = new PDO('mysql:host=localhost;dbname=BowlingSYS; charset=utf8', 'root', '');
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     $sql = 'DELETE FROM booking WHERE bookingid = :cid';
     $result = $pdo->prepare($sql);
     $result->bindValue(':cid', $_POST['id']);
     $result->execute();


     echo "You just deleted booking no: " . $_POST['id'] . " \n click<a href='CurrentSession.php'> here</a> to go back ";

} catch (PDOException $e) {

     if ($e->getCode() == 23000) {
          echo "ooops couldnt delete as that record is linked to other tables click<a href='view all update delete.php'> here</a> to go back ";
     }

}
?>