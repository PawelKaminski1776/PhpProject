<?php
include 'Login.html';
if (isset($_POST['submitdetails'])) {
    try {
        $cemail = $_POST['cemail'];
        $cpassword = $_POST['cpassword'];
        $errors = array();
        if ($cemail == '') {
            $errors[] = 'Email is required.';
        }
        if ($cpassword == '') {
            $errors[] = 'Password is required.';
        }
        if (!empty($errors)) {
            echo '<div class="alert alert-danger"><ul>';
            foreach ($errors as $error) {
                echo "<li>$error</li>";
            }
            echo '</ul></div>';
        } else {
            $pdo = new PDO('mysql:host=localhost;dbname=BowlingSYS; charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $email_sql = "SELECT email FROM customer WHERE email = :cemail";
            $email_stmt = $pdo->prepare($email_sql);
            $email_stmt->bindValue(':cemail', $cemail);
            $email_stmt->execute();
            if ($email_stmt->rowCount() > 0) {
                $password_sql = "SELECT password FROM customer WHERE password = :cpassword";
                $password_stmt = $pdo->prepare($password_sql);
                $password_stmt->bindValue(':cpassword', $cpassword);
                $password_stmt->execute();
                if ($password_stmt->rowCount() > 0) {
                    $custid_sql = "SELECT custid FROM customer WHERE password = :cpassword AND email = :cemail";
                    $custid_stmt = $pdo->prepare($custid_sql);
                    $custid_stmt->bindValue(':cpassword', $cpassword);
                    $custid_stmt->bindValue(':cemail', $cemail);
                    $custid_stmt->execute();
                    $custid_result = $custid_stmt->fetch();
                    if ($custid_result) {
                        session_start();
                        $_SESSION["username"] = $custid_result['custid'];
                        header('Location: CurrentSession.php');
                        exit();
                    }
                } else {
                    echo "<script>alert('Incorrect Password');</script>";
                }

            } else {
                echo "<script>alert('Incorrect Details');</script>";
            }

        }
    } catch (PDOException $e) {
        $title = 'An error has occurred';
        $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
    }
}
?>