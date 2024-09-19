<?php
session_start();

echo '<div class="navi">
<h2 class="navibuttons"><a href="MakeBooking.html">Make a Booking</a></h2>
<h2 class="navibuttons"><a href="Account.php">Account</a></h2>
<h2 class="navibuttons"><a href="Login.html">Log Out</a></h2>
</div>';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not
    header('Location: login.php');
    exit();
}

// Connect to the database
try {
    $pdo = new PDO('mysql:host=localhost;dbname=BowlingSYS;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle database connection errors
    echo "Database connection failed: " . $e->getMessage();
    exit();
}

// Retrieve the name of the logged-in user
$name_sql = "SELECT forename, surname FROM customer WHERE custid = :username";
$name_stmt = $pdo->prepare($name_sql);
$name_stmt->bindValue(':username', $_SESSION['username']);
$name_stmt->execute();
$name_row = $name_stmt->fetch(PDO::FETCH_ASSOC);
$forename = $name_row['forename'];
$surname = $name_row['surname'];

echo '<div class="main"><h2>Welcome to BowlingSYS!<br>' . $forename . " " . $surname . '</h2></div>';

try {
    $pdo = new PDO('mysql:host=localhost;dbname=BowlingSYS; charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT Booking.bookingid, Booking.laneid, Booking.bookingdate, Booking.time, Booking.status, Booking.price, Customer.forename, Customer.surname FROM Booking INNER JOIN Customer ON Booking.custid = Customer.custid WHERE Booking.custid = :username AND Booking.status = 'B'";
    $result = $pdo->prepare($sql);
    $result->bindValue(':username', $_SESSION['username']);
    $result->execute();


    echo '<div class = "tables"><table><caption>All Bookings</caption>';
    echo "<thead><tr><th>Booking Id</th>
<th>Name</th><th>Lane Id</th><th>Date</th><th>Time</th><th>Status</th><th>Price</th><th>Remove</th><th>Update</th></tr></thead>";

    echo "<tbody>";

    while ($row = $result->fetch()) {
        echo '<tr><td>' . $row['bookingid'] . '</td><td>' . $row['forename'] . ' ' . $row['surname'] . '</td><td>' . $row['laneid'] . '</td><td>' . $row['bookingdate'] . '</td><td>' . $row['time'] . '</td><td>' . $row['status'] . '</td><td>' . $row['price'] . '</td> ';
        echo "<td><a href=\"delete.php?bookingid=" . $row['bookingid'] . "\">Remove</a></td>";
        echo "<td><a href=\"updatebookingform.php?bookingid=" . $row['bookingid'] . "\">Update</a></td>";
        echo "</tr>";
    }
    echo '</tbody></table></div><br><br>';
} catch (PDOException $e) {
    $output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
}

// Include the logged-on page template
include('footer.html');