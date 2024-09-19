<?php


try {
    $pdo = new PDO('mysql:host=localhost;dbname=BowlingSYS; charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT count(*) FROM booking WHERE bookingid=:cid";

    $result = $pdo->prepare($sql);
    $result->bindValue(':cid', $_GET['bookingid']);
    $result->execute();
    if ($result->fetchColumn() > 0) {
        $sql = 'SELECT * FROM booking where bookingid = :cid';
        $result = $pdo->prepare($sql);
        $result->bindValue(':cid', $_GET['bookingid']);
        $result->execute();

        $row = $result->fetch();
        $id = $row['bookingid'];
        $bookingdate = $row['bookingdate'];
        $time = $row['time'];
        $numofpeople = $row['NumOfPeople'];

    } else {
        print "No rows matched the query. try again click<a href='view all update delete.php'> here</a> to go back";
    }
} catch (PDOException $e) {
    $output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
}


include 'updatebookingdetails.html';
?>