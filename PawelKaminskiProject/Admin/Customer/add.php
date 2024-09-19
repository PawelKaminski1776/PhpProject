<?php
if (isset($_POST['submitdetails'])) {
    try {
        $cforename = $_POST['cforename'];
        $csurname = $_POST['csurname'];
        $cpassword = $_POST['cpassword'];
        $ctelephone = $_POST['ctelephone'];
        $cemail = $_POST['cemail'];

        $valid = true;

        if ($cforename == '' or $csurname == '' or $ctelephone == '' or $cemail == '' or $cpassword == '' or is_numeric($cforename) or is_numeric($csurname) or !is_numeric($ctelephone)) {
            echo "<script>alert('You did not complete the insert form correctly');</script>";
            $valid = false;
        }
        if (!str_ends_with($cemail, '@gmail.com')) {
            echo "<script>alert('Not a valid email');</script>";
            $valid = false;
        }


        $pdo = new PDO('mysql:host=localhost;dbname=BowlingSYS; charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $email_sql = "SELECT email FROM customer WHERE email = :cemail";
        $email_stmt = $pdo->prepare($email_sql);
        $email_stmt->bindValue(':cemail', $cemail);
        $email_stmt->execute();
        if ($email_stmt->rowCount() > 0) {
            echo "<script>alert('Email already exists');</script>";
            $valid = false;
        }

        if ($valid == true) {
            $custcount_sql = "SELECT MAX(laneid) FROM lane";
            $custcount_stmt = $pdo->prepare($custcount_sql);
            $custcount_stmt->execute();
            $autoincrement_value = $custcount_stmt->fetchColumn();
            if ($autoincrement_value !== false) {
                $autoincrement_sql = "ALTER TABLE customer AUTO_INCREMENT = " . ($autoincrement_value + 1);
            } else {
                $autoincrement_sql = "ALTER TABLE customer AUTO_INCREMENT = 1";
            }
            $autoincrement_stmt = $pdo->prepare($autoincrement_sql);
            $autoincrement_stmt->execute();
            $sql = "INSERT INTO customer (forename,surname,telephone,email,password) VALUES(:cforename, :csurname,             
                    :ctelephone, :cemail, :cpassword)";

            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':cforename', $cforename);
            $stmt->bindValue(':csurname', $csurname);
            $stmt->bindValue(':cpassword', $cpassword);
            $stmt->bindValue(':ctelephone', $ctelephone);
            $stmt->bindValue(':cemail', $cemail);

            $stmt->execute();
            
            header('Location: view all update delete.php');
        }
    } catch (PDOException $e) {
        $title = 'An error has occurred';
        $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
    }
}

include 'addform.html';
echo "click<a href='view all update delete.php'> here</a> to go back";
?>