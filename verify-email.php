<?php 
session_start();
include('dbcon.php');

if(isset($_GET['token'])) {
    $token = $_GET['token'];
    $verify_query = "SELECT * FROM users WHERE varify_token='$token' LIMIT 1";
    $verify_query_run = mysqli_query($conn, $verify_query);

    // Check if query execution was successful
    if(!$verify_query_run) {
        die('Error in SQL query: ' . mysqli_error($conn));
    }
     
    // Check if any rows were returned
    if(mysqli_num_rows($verify_query_run) > 0) {
        $row = mysqli_fetch_array($verify_query_run);
        if($row['verify_status'] == "0") {
            $clicked_token = $row['varify_token'];
            $update_query = "UPDATE users SET verify_status='1' WHERE varify_token='$clicked_token' LIMIT 1";
            $update_query_run = mysqli_query($conn, $update_query);

            // Check if update query execution was successful
            if($update_query_run) {
                $_SESSION['status'] = 'Your Account has been Verified Successfully';
                header('Location: login.php');
                exit(0);
            } else {
                $_SESSION['status'] = 'Verification Failed';
                header('Location: login.php');
                exit(0);
            }
        } else {
                
             $_SESSION['status'] = 'Email Already Verified. Please Login';
             header('Location: login.php');
             exit(0);
        }
    } else {
        $_SESSION['status'] = 'This Token does not Exist';
        header('Location: login.php');
        exit(0);
    }
} else {
    $_SESSION['status'] = 'Not Allowed';
    header('Location: login.php');
    exit(0);
}
?>
