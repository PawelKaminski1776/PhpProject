<?php
include 'header.html';
try {
    $pdo = new PDO('mysql:host=localhost;dbname=BowlingSYS; charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $lanecount_sql = "SELECT MAX(laneid) FROM lane";
    $lanecount_stmt = $pdo->prepare($lanecount_sql);
    $lanecount_stmt->execute();
    $autoincrement_value = $lanecount_stmt->fetchColumn();
    if ($autoincrement_value !== false) {
        $autoincrement_sql = "ALTER TABLE lane AUTO_INCREMENT = " . ($autoincrement_value + 1);
    } else {
        $autoincrement_sql = "ALTER TABLE lane AUTO_INCREMENT = 1";
    }
    $autoincrement_stmt = $pdo->prepare($autoincrement_sql);
    $autoincrement_stmt->execute();

    $sql = "INSERT INTO lane (status) VALUES('A')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    header('Location: view all update delete.php');

} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
}


?>