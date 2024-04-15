<?php 
session_start();
  include('dbcon.php');
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

  require 'vendor/autoload.php';

  function  resend_email_verify($name,$email,$verify_token)
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'prasadkamireddy3@gmail.com';                 //SMTP username
        $mail->Password   = 'wizljjwejuaqpgkx';                        //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom('prasadkamireddy3@gmail.com',$name);
        $mail->addAddress($email);                                   //Add a recipient
        
        //Content
        $mail->isHTML(true);                                        //Set email format to HTML
        $mail->Subject = 'Resend-Email Varification from prasadtech'; 
        $mail->Body    = "<h2>You have Registered with prasadtech</h2> 
                         <h5>Verify your email adddress to login with the below given link</h5><br/><br/>
        <a href='http://localhost/emailvarify/verify-email.php?token=$verify_token'>
        Click Me</a>";
       
        $mail->send();
        return true;
    }
if(isset($_POST['resend_email_verify_btn']))
{
    if(!empty(trim($_POST['email'])))
     {
          $email = mysqli_real_escape_string($conn,$_POST['email']);

          $checkemail_query ="SELECT * FROM users WHERE email='$email' LIMIT 1";
          $checkemail_query_run = mysqli_query($conn, $checkemail_query);

          if(mysqli_num_rows($checkemail_query_run) > 0)
           {
             $row = mysqli_fetch_array($checkemail_query_run);
             if($row['verify_status'] == "0")
              {
                  $name = $row['name'];
                  $email = $row['email'];
                  $verify_token = $row['varify_token'];
                resend_email_verify($name,$email,$verify_token);
                $_SESSION['status'] ="Verification Email link has been sent to your email address";
                header("location:login.php");
                exit(0);
                
              }
              else
               {
                $_SESSION['status'] ="Email already verified.Please login";
                header("location:resend-email-verification.php");
                exit(0);
               }
           }
           else
           {
            $_SESSION['status'] ="Email is not registered.please Register now!";
            header("location:register.php");
            exit(0);
           }
      }
     else
      {
        $_SESSION['status'] ="please enter the email field";
        header("location:resend-email-verification.php");
        exit(0);
      }
    
  }
?>


  