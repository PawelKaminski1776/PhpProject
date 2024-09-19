<?php


if (isset($_POST['SubmitDetails'])) {
    $status = $_POST['ud_status'];

echo $status;

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=BowlingSYS; charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'update lane set status=:cstatus WHERE laneid = :lid';
        $result = $pdo->prepare($sql);
        $result->bindValue(':lid', $_POST['ud_id']);
        $result->bindValue(':cstatus', $status);
        $result->execute();

        //For most databases, PDOStatement::rowCount() does not return the number of rows affected by a SELECT statement.

        $count = $result->rowCount();
        if ($count > 0) {
            header('Location: view all update delete.php');
        } else {
            header('Location: view all update delete.php');
        }
    } catch (PDOException $e) {

        $output = 'Unable to process query sorry : ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();

    }
}
?>