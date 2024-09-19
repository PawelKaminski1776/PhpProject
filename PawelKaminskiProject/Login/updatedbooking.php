<?php

include('updatebookingdetails.html');

$bookingid = $_POST['ud_id'];
$bookingdate = date("Y-m-d", strtotime($_POST['ud_bookingdate']));
$numofpeople = $_POST['ud_numofpeople'];
$time = $_POST['ud_time'];
$price = 15 + (2.5 * $numofpeople);
$current_time = time();
$date_input_timestamp = strtotime($bookingdate);

$cardnum = $_POST['ccardnumber'];
$expirymonth = $_POST['cexpirymonth'];
$expiryyear = $_POST['cexpiryyear'];
$securitynumber = $_POST['csecuritynumber'];

if ($date_input_timestamp < $current_time) {
    echo "<script>alert('Error: Date input must be after current system time.');</script>";
} elseif ($cardnum == '' || $expirymonth == '' || $expiryyear == '' || $securitynumber == '') {
    echo "<script>alert('Error: please enter your credit card details');</script>";
} elseif (!is_numeric($cardnum) || !is_numeric($securitynumber) || strlen($cardnum) != 16 || strlen($securitynumber) != 3) {
    echo "<script>alert('Error: incorrect credit card details')</script>";
} else {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=BowlingSYS; charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $clane_sql = "SELECT MAX(laneid) FROM lane WHERE status = 'A'";
        $clane_stmt = $pdo->prepare($clane_sql);
        $clane_stmt->execute();
        $clane_result = $clane_stmt->fetch(PDO::FETCH_ASSOC);

        $valid = false;

        for ($claneid = 1; $claneid <= $clane_result['MAX(laneid)']; $claneid++) {
            $cbooking_sql = "SELECT * FROM Booking WHERE bookingdate = :cbookingdate AND time = :ctime AND laneid = :claneid AND status = 'B'";
            $cbooking_stmt = $pdo->prepare($cbooking_sql);
            $cbooking_stmt->bindValue(':cbookingdate', $bookingdate);
            $cbooking_stmt->bindValue(':ctime', $time);
            $cbooking_stmt->bindValue(':claneid', $claneid);
            $cbooking_stmt->execute();
            $cbooking_result = $cbooking_stmt->fetch(PDO::FETCH_ASSOC);

            if ($cbooking_result) {
                $valid = false;
            } else {
                $valid = true;
                break;
            }
        }

        if($valid == false){
            echo "<script>alert('Error: No lanes available at this time.');</script>";
        }

        if ($valid == true) {
            $sql = 'update booking set bookingdate = :cbookingdate,time = :ctime, NumOfPeople = :cnumofpeople,price = :cprice  WHERE bookingid = :cid';
            $result = $pdo->prepare($sql);
            $result->bindValue(':cid', $bookingid);
            $result->bindValue(':cbookingdate', $bookingdate);
            $result->bindValue(':ctime', $time);
            $result->bindValue(':cnumofpeople', $numofpeople);
            $result->bindValue(':cprice', $price);
            $result->execute();

            header("Location: CurrentSession.php");
        }

    } catch (PDOException $e) {
        $output = 'Unable to process query sorry : ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();

    }
}
?>