<?php

session_start();

include('MakeBooking.html');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

try {
    $ccustid = $_SESSION['username'];
    $cbookingdate = date("Y-m-d", strtotime($_POST['cbookingdate']));
    $ctime = $_POST['ctime'];
    $cstatus = "B";
    $cnumofpeople = $_POST['cnumofpeople'];
    $cprice = 15 + ($cnumofpeople * 2.5);
    $claneid = 1;
    $current_time = time(); 
    $date_input_timestamp = strtotime($cbookingdate); 
    $cardnum = $_POST['ccardnumber'];
    $expirymonth = $_POST['cexpirymonth'];
    $expiryyear = $_POST['cexpiryyear'];
    $securitynumber = $_POST['csecuritynumber'];


    if ($date_input_timestamp < $current_time) {
        echo "<script>alert('Error: Date input must be after current system time.');</script>";
    } 

    if ($cardnum == '' || $expirymonth == '' || $expiryyear == '' || $securitynumber == ''){
        echo "<script>alert('Error: please enter your credit card details');</script>";
    }

    if (!is_numeric($cardnum) || !is_numeric($securitynumber) || strlen($cardnum) != 16 || strlen($securitynumber) != 3){
        echo "<script>alert('Error: incorrect credit card details');</script>";
    }

    else {
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
            $cbooking_stmt->bindValue(':cbookingdate', $cbookingdate);
            $cbooking_stmt->bindValue(':ctime', $ctime);
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
        if ($valid == false) {
            echo "<script>alert('Error: No lanes available at this time.');</script>";
        }
        if ($valid == true) {
            $bookingcount_sql = "SELECT MAX(bookingid) FROM booking";
            $bookingcount_stmt = $pdo->prepare($bookingcount_sql);
            $bookingcount_stmt->execute();
            $autoincrement_value = $bookingcount_stmt->fetchColumn();
            if ($autoincrement_value !== false) {
                $autoincrement_sql = "ALTER TABLE booking AUTO_INCREMENT = " . ($autoincrement_value + 1);
            } else {
                $autoincrement_sql = "ALTER TABLE booking AUTO_INCREMENT = 1";
            }
            $autoincrement_stmt = $pdo->prepare($autoincrement_sql);
            $autoincrement_stmt->execute();
   
            $sql = "INSERT INTO booking (laneid,custid,bookingdate,time,numofpeople,status,price) VALUES(:claneid, :ccustid, :cbookingdate, :ctime, :cnumofpeople,:cstatus, :cprice)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':claneid', $claneid);
            $stmt->bindValue(':ccustid', $ccustid);
            $stmt->bindValue(':cbookingdate', $cbookingdate);
            $stmt->bindValue(':ctime', $ctime);
            $stmt->bindValue(':cnumofpeople', $cnumofpeople);
            $stmt->bindValue(':cstatus', $cstatus);
            $stmt->bindValue(':cprice', $cprice);
            $stmt->execute();

            header('Location: CurrentSession.php');
            exit();
        }
    }
} catch (PDOException $e) {
    $output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
}

?>