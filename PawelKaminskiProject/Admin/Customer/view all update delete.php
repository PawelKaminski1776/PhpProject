<?php
include 'header.html';
try {
      $pdo = new PDO('mysql:host=localhost;dbname=BowlingSYS; charset=utf8', 'root', '');
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $sql = 'SELECT * FROM customer';
      $result = $pdo->query($sql);

      echo '<div class = "maintext"><h2>Manage Customers:</h2>';

      echo '<p>Here you can Add, Update and delete Lanes!</p>';

      echo '<br /><b>A Quick View</b><br><br>';
      echo '<div class = "tables"><table border=1><caption>All Customers</caption>';
      echo "<tr><th>User Id</th>
<th>FirstName:</th><th>Delete</th><th>Update:</th></tr>";


      while ($row = $result->fetch()) {
            echo '<tr><td>' . $row['custid'] . '</td><td>' . $row['forename'] . ' ' . $row['surname'] . '</td>';
            echo "<td><a href=\"deletecustomer.php?custid=" . $row['custid'] . "\">Remove</a></td>";
            echo "<td><a href=\"updateform.php?custid=" . $row['custid'] . "\">Update</a></td>";
            echo "</tr>";




      }
      echo '</table></div>';
} catch (PDOException $e) {
      $output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
}

echo '<p class="button"><a href="addform.html">Add Customer</a></p></div>';

echo '<div class="footer"></div>'


      ?>