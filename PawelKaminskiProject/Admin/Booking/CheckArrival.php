<?php

date_default_timezone_set('Europe/Dublin');

try {
    $timeout = date('Y-m-d H:i:s', strtotime('+1 hour'));
    $pdo = new PDO('mysql:host=localhost;dbname=BowlingSYS; charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE booking SET status = 'F', timeout = :ctimeout WHERE bookingid = :cid";
    $result = $pdo->prepare($sql);
    $result->bindValue(':cid', $_GET['bookingid']);
    $result->bindValue(':ctimeout', $timeout);
    $result->execute();

    $lanestatus_sql = "UPDATE lane INNER JOIN booking ON lane.laneid = booking.laneid SET lane.status = 'U' WHERE booking.bookingid = :cid";
    $lanestatus_result = $pdo->prepare($lanestatus_sql);
    $lanestatus_result->bindValue(':cid', $_GET['bookingid']);
    $lanestatus_result->execute();

    $lane_timeout_sql = "UPDATE lane INNER JOIN booking ON lane.laneid = booking.laneid SET lane.status = 'A' WHERE booking.bookingid = :cid AND booking.timeout <= DATE_SUB(NOW(), INTERVAL 1 HOUR)";
    $lane_timeout_result = $pdo->prepare($lane_timeout_sql);
    $lane_timeout_result->bindValue(':cid', $_GET['bookingid']);
    $lane_timeout_result->execute();

    header('Location: view all update delete.php');
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        echo "Error";
    }
}

?>