<?php 
session_start();
include('dbcon.php');
 use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

  require 'vendor/autoload.php';
function  send_password_reset($get_name,$get_email,$token)
 {
    $mail = new PHPMailer(true);
    $mail->isSMTP();                                           
    $mail->Host       = 'smtp.gmail.com';                      
    $mail->SMTPAuth   = true;                                 
    $mail->Username   = 'prasadkamireddy3@gmail.com';                 
    $mail->Password   = 'wizljjwejuaqpgkx';                         
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
    $mail->Port       = 465;                                  
    //Recipients
    $mail->setFrom('prasadkamireddy3@gmail.com',$get_name);
    $mail->addAddress($get_email);                                   //Add a recipient
    
    //Content
    $mail->isHTML(true);                                        //Set email format to HTML
    $mail->Subject = 'Reset Password Notification'; 
    $mail->Body    = "<h2>You are receiving this email because we received a password reset request for your account.</h2> 
                     <h5>Verify your email adddress to login with the below given link</h5><br/><br/>
    <a href='http://localhost/emailvarify/password-change.php?token=$token&email=$get_email'>
    Click Me</a>";
   
    $mail->send();
    return true;
 }

if(isset($_POST['password_reset_btn']))
 {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $token = md5(rand());

    $check_email ="SELECT email FROM users WHERE email='$email' LIMIT 1";
    $check_email_run = mysqli_query($conn,$check_email);

    if(mysqli_num_rows($check_email_run) > 0)
     {
         $row = mysqli_fetch_array($check_email_run);
         $get_name = $row['name'];
         $get_email = $row['email'];

         $update_token ="UPDATE users SET varify_token='$token' WHERE email='$get_email' LIMIT 1";
         $update_token_run = mysqli_query($conn, $update_token);

         if($update_token_run )
          {
              send_password_reset($get_name,$get_email,$token);
              $_SESSION['status'] = "we emailed you a password reset link";
              header("Location:password-reset.php");
              exit(0);
          }
          else
           {
            $_SESSION['status'] = "Something Went Wrong.#1";
            header("Location:password-reset.php");
            exit(0);
           }
     }
     else
      {
        $_SESSION['status'] = "No Email Found";
        header("Location:password-reset.php");
        exit(0);
      }
 }

 if(isset($_POST['password_update']))
  {
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $new_password = mysqli_real_escape_string($conn,$_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn,$_POST['confirm_password']);
    $token = mysqli_real_escape_string($conn,$_POST['password_token']);

    if(!empty($token))
     {
          if(!empty($email) && !empty($new_password ) && !empty($confirm_password))
          {
            //checking Token is valid or not
            $check_token ="SELECT varify_token FROM users WHERE varify_token='$token' LIMIT 1";
            $check_token_run = mysqli_query($conn,$check_token);

            if(mysqli_num_rows($check_token_run) > 0)
             {
                if($new_password == $confirm_password)
                 {
                    $update_password ="UPDATE users SET password='$new_password' WHERE varify_token='$token' LIMIT 1";
                    $update_password_run = mysqli_query($conn,$update_password);

                    if($update_password_run)
                     {  
                        $new_token = md5(rand()).'prasadtech';
                        $update_to_new_token ="UPDATE users SET varify_token='$new_token' WHERE varify_token='$token' LIMIT 1";
                        $update_to_new_token_run = mysqli_query($conn,$update_to_new_token);
    
                        $_SESSION['status'] ="new password Successfully updated!";
                        header("location:login.php");
                        exit(0);
                     }
                     else
                     {
                        $_SESSION['status'] ="Did not update password something went wrong!";
                        header("location:password-change.php?token=$token&email=$email");
                        exit(0);
                     }
                 }
                 else
                 {
                    $_SESSION['status'] ="Password and Confirm Password does not match";
                    header("location:password-change.php?token=$token&email=$email");
                    exit(0);
                 }
             }
             else
              {
                $_SESSION['status'] ="Invalid Token";
                header("location:password-change.php?token=$token&email=$email");
                 exit(0);
              }
          }
          else
           {
            $_SESSION['status'] ="All Filed are Mandatory";
            header("location:password-change.php?token=$token&email=$email");
             exit(0);

           }
    }
     else
      {
        $_SESSION['status'] ="No Taken Avalible";
        header("location:password-change.php");
        exit(0);
      }
  }


?>