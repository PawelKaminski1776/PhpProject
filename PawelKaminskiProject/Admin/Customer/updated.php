<?php
if (isset($_POST['submitdetails'])) {
    $ud_id = $_POST['ud_id'];
    $ud_forename = $_POST['ud_forename'];
    $ud_surname = $_POST['ud_surname'];
    $ud_password = $_POST['ud_password'];
    $ud_telephone = $_POST['ud_telephone'];
    $ud_email = $_POST['ud_email'];


    $valid = true;

    // check if email exists
    $pdo = new PDO('mysql:host=localhost;dbname=BowlingSYS; charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $email_sql = "SELECT email FROM customer WHERE email = :cemail";
    $email_stmt = $pdo->prepare($email_sql);
    $email_stmt->bindValue(':cemail', $ud_email);
    $email_stmt->execute();
    if ($email_stmt->rowCount() > 0) {
        $valid = false;
    }

    // check if its the same email
    $emailcheck_sql = "SELECT email FROM customer WHERE custid = :cid";
    $emailcheck_stmt = $pdo->prepare($emailcheck_sql);
    $emailcheck_stmt->bindValue(':cid', $ud_id);
    $emailcheck_stmt->execute();
    $result = $emailcheck_stmt->fetchColumn();

    if ($result == $ud_email) {
        $valid = true;
    }


    // validation
    if ($ud_forename == '' or $ud_surname == '' or $ud_telephone == '' or $ud_email == '' or $ud_password == '' or is_numeric($ud_forename) or is_numeric($ud_surname) or !is_numeric($ud_telephone)) {
        echo ("You did not complete the insert form correctly <br> ");
        $valid = false;
    }

    // email validation
    if (!str_ends_with($ud_email, '@gmail.com')) {
        echo ("Error: email is incorrect");
        $valid = false;
    }

    // if false go back without changing anything
    if ($valid == false) {
        header('updateAccount.php');
    }

    // if true update
    if ($valid == true) {

        try {
            $pdo = new PDO('mysql:host=localhost;dbname=BowlingSYS; charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'update customer set forename=:cforename,surname = :csurname, password = :cpassword, telephone = :ctelephone,email = :cemail WHERE custid = :cid';
            $result = $pdo->prepare($sql);
            $result->bindValue(':cid', $_POST['ud_id']);
            $result->bindValue(':cforename', $ud_forename);
            $result->bindValue(':csurname', $ud_surname);
            $result->bindValue(':cpassword', $ud_password);
            $result->bindValue(':ctelephone', $ud_telephone);
            $result->bindValue(':cemail', $ud_email);
            $result->execute();

            //For most databases, PDOStatement::rowCount() does not return the number of rows affected by a SELECT statement.

            $count = $result->rowCount();
            if ($count > 0) {
                echo header('Location: view all update delete.php');
            } else {
                echo header('Location: view all update delete.php');
            }
        } catch (PDOException $e) {

            $output = 'Unable to process query sorry : ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();

        }
    }
}
?>