<?php
include 'header.html';
try {
      $pdo = new PDO('mysql:host=localhost;dbname=BowlingSYS; charset=utf8', 'root', '');
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $sql = 'SELECT * FROM lane';
      $result = $pdo->query($sql);

      echo '<div class = "maintext"><h2>Manage Lanes:</h2>';

      echo '<p>Here you can Add, Update and delete Lanes!</p>';

      echo '<br /><b>A Quick View</b><br><br>';
      echo '<div class="tables"><table border=1><caption>All Lanes</caption>';
      echo "<tr><th>Lane Id</th>
<th>Status:</th><th>Delete</th><th>Update:</th></tr>";


      while ($row = $result->fetch()) {
            echo '<tr><td>' . $row['laneid'] . '</td><td>' . $row['status'] . '</td>';
            echo "<td><a href=\"deletelane.php?laneid=" . $row['laneid'] . "\">Remove</a></td>";
            echo "<td><a href=\"updateform.php?laneid=" . $row['laneid'] . "\">Update</a></td>";
            echo "</tr>";
      }
      echo '</table></div>';
      echo '<br><p class="button"><a href="add.php">Add a Lane</a></p></div>';
} catch (PDOException $e) {
      $output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
}

echo '<div class="footer"></div>'

      ?>