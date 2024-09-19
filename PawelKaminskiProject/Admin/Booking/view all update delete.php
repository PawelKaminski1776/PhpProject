<?php
include 'header.html';
try {
      $pdo = new PDO('mysql:host=localhost;dbname=BowlingSYS; charset=utf8', 'root', '');
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $sql = "SELECT Booking.bookingid, Customer.custid, Customer.forename, Customer.surname, Booking.laneid,Booking.bookingdate, Booking.time, Booking.status, Booking.price FROM Booking INNER JOIN Customer ON Booking.custid = Customer.custid WHERE Booking.status = 'B'";
      $result = $pdo->query($sql);

      echo '<div class = "maintext"><h2>Manage Bookings:</h2>';

      echo '<p>Here you can Add, Update, Delete and Check Arrival of Bookings!</p>';

      echo '<br /><b>A Quick View</b><br><br>';
      echo '<div class="tables"><table><caption>All Bookings</caption>';
      echo "<thead><tr><th>Booking Id</th>
<th>Name</th><th>Lane Id</th><th>Date</th><th>Time</th><th>Status</th><th>Price</th><th>Remove</th><th>Update</th><th>Check Arrival</th></tr></thead>";

      echo "<tbody>";

      while ($row = $result->fetch()) {
            echo '<tr><td>' . $row['bookingid'] . '</td><td>' . $row['forename'] . ' ' . $row['surname'] . '</td><td>' . $row['laneid'] . '</td><td>' . $row['bookingdate'] . '</td><td>' . $row['time'] . '</td><td>' . $row['status'] . '</td><td>' . $row['price'] . '</td> ';
            echo "<td><a href=\"deletebooking.php?bookingid=" . $row['bookingid'] . "\">Remove</a></td>";
            echo "<td><a href=\"updateform.php?bookingid=" . $row['bookingid'] . "\">Update</a></td>";
            echo "<td><a href=\"CheckArrival.php?bookingid=" . $row['bookingid'] . "\">Check Arrival</a></td>";
            echo "</tr>";
      }
      echo '</tbody></table></div><br>';

      $finished_sql = "SELECT Booking.bookingid, Customer.custid, Customer.forename, Customer.surname, Booking.laneid,Booking.bookingdate, Booking.time, Booking.status, Booking.price FROM Booking INNER JOIN Customer ON Booking.custid = Customer.custid WHERE Booking.status = 'F'";
      $finished_result = $pdo->query($finished_sql);

      echo '<div class="tables"><table><caption>All Finished Bookings</caption>';
      echo "<thead><tr><th>Booking Id</th>
<th>Name</th><th>Lane Id</th><th>Date</th><th>Time</th><th>Status</th><th>Price</th><th>Remove</th></tr></thead>";

      echo "<tbody>";

      while ($finished_row = $finished_result->fetch()) {
            echo '<tr><td>' . $finished_row['bookingid'] . '</td><td>' . $finished_row['forename'] . ' ' . $finished_row['surname'] . '</td><td>' . $finished_row['laneid'] . '</td><td>' . $finished_row['bookingdate'] . '</td><td>' . $finished_row['time'] . '</td><td>' . $finished_row['status'] . '</td><td>' . $finished_row['price'] . '</td> ';
            echo "<td><a href=\"deletebooking.php?bookingid=" . $finished_row['bookingid'] . "\">Remove</a></td>";
            echo "</tr>";
      }
      echo '</tbody></table></div>';
      


} catch (PDOException $e) {
      $output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
}

echo '<p class="button"><a href="addform.html">Add a Booking</a></p></div>';

echo '<div class="footer"></div>'

?>