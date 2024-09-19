<?php session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not
    header('Location: login.php');
    exit();
}

include('UpdateAccount.html');

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
$emailcheck_sql = "SELECT email FROM customer WHERE custid = :username";
$emailcheck_stmt = $pdo->prepare($emailcheck_sql);
$emailcheck_stmt->bindValue(':username', $_SESSION['username']);
$emailcheck_stmt->execute();
$result = $emailcheck_stmt->fetchColumn();

if ($result == $ud_email) {
    $valid = true;
}


// validation
if ($ud_forename == '' or $ud_surname == '' or $ud_telephone == '' or $ud_email == '' or $ud_password == '' or is_numeric($ud_forename) or is_numeric($ud_surname) or !is_numeric($ud_telephone)) {
    echo "<script>alert('You did not complete the insert form correctly');</script>";
    $valid = false;
}

// email validation
if (!str_ends_with($ud_email, '@gmail.com')) {
    echo "<script>alert('Error: email must be a gmail account');</script>";
    $valid = false;
}

if ($valid == false) {
    echo "<script>alert('Error: email already exists');</script>";
    header('updateAccount.php');
}

// if valid is true update
if ($valid == true) {
    try {
        $sql = 'UPDATE customer SET forename=:cforename, surname=:csurname, password=:cpassword, telephone=:ctelephone, email=:cemail WHERE custid=:username';
        $result = $pdo->prepare($sql);
        $result->bindValue(':username', $_SESSION['username']);
        $result->bindValue(':cforename', $_POST['ud_forename']);
        $result->bindValue(':csurname', $_POST['ud_surname']);
        $result->bindValue(':cpassword', $_POST['ud_password']);
        $result->bindValue(':ctelephone', $_POST['ud_telephone']);
        $result->bindValue(':cemail', $_POST['ud_email']);
        $result->execute();

        $rowCount = $result->rowCount();
        if ($rowCount > 0) {
            echo header('Location: Account.php');
        } else {
            echo header('Location: Account.php');
        }
    } catch (PDOException $e) {
        $errorMessage = $e->getMessage();
        echo "Unable to update your profile. Error: $errorMessage";
    }
}
?>