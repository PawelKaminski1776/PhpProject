<?php
session_start();


echo '<div class="navi">
<h2 class="navibuttons"><a href="MakeBooking.html">Make a Booking</a></h2>
<h2 class="navibuttons"><a href="CurrentSession.php">Main Page</a></h2>
<h2 class="navibuttons"><a href="Login.html">Log Out</a></h2>
</div>';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not
    //header('Location: login.php');
    exit();
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=BowlingSYS;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit();
}

// Retrieve the details of the logged-in user
$details_sql = "SELECT forename, surname, email, telephone FROM customer WHERE custid = :username";
$details_stmt = $pdo->prepare($details_sql);
$details_stmt->bindValue(':username', $_SESSION['username']);
$details_stmt->execute();
$details_row = $details_stmt->fetch(PDO::FETCH_ASSOC);
$forename = $details_row['forename'];
$surname = $details_row['surname'];
$email = $details_row['email'];
$telephone = $details_row['telephone'];

$bookingcount_sql = "SELECT COUNT(*) FROM booking WHERE custid = :username";
$bookingcount_stmt = $pdo->prepare($bookingcount_sql);
$bookingcount_stmt->bindValue(':username', $_SESSION['username']);
$bookingcount_stmt->execute();
$TotalBookings_value = $bookingcount_stmt->fetchColumn();

if ($TotalBookings_value === null) {
    $TotalBookings_value = 0;
} 

echo '<div class="maintext">
      <h2>Account Settings</h2>
      <p>Account Details: </p>
      <p>Name: ' . $forename . " " . $surname .
    '<p>Email: ' . $email .'</p>' . 
    '<p>Phone: ' . $telephone .'</p>' . 
    '<p>No. of bookings: ' . $TotalBookings_value . '</p>' .
    '<p><a href="updateAccount.php">Update Account</a></p>
     <p><a href="deleteconfirmation.php">Delete Account</a></p>
     </div>';
    include "footer.html";
?>