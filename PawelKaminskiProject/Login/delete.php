<?php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=BowlingSYS; charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'SELECT count(*) FROM booking where bookingid = :cid';
    $result = $pdo->prepare($sql);
    $result->bindValue(':cid', $_GET['bookingid']);
    $result->execute();

    if ($result->fetchColumn() > 0) {
        $sql = 'SELECT * FROM booking where bookingid = :cid';
        $result = $pdo->prepare($sql);
        $result->bindValue(':cid', $_GET['bookingid']);
        $result->execute();

        while ($row = $result->fetch()) {

            echo $row['bookingid'] . ' ' . $row['price'] . ' Are you sure you want to delete ??' . '<form action="deletebooking.php" method="post">
            <input type="hidden" name="id" value="' . $row['bookingid'] . '"> 
            <input type="submit" value="yes delete" name="delete">
        </form>';

        }
    } else {
        print "No rows matched the query.";
    }
} catch (PDOException $e) {
    $output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
}



?>