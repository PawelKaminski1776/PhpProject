<?php
session_start();

echo "<script>
    if (confirm('Are you sure you want to delete your account?')) {
        window.location.href = 'deleteaccount.php';
    } else {
        window.location.href = 'Account.php'; 
    }
</script>";

?>

