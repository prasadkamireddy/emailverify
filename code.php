<?php 
  session_start();
  include('dbcon.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';
   function sendemail_verify($name,$email,$verify_token)
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
        $mail->setFrom('prasadkamireddy3@gmail.com', 'prasadtech');
        $mail->addAddress($email);                                   //Add a recipient
        
        //Content
        $mail->isHTML(true);                                        //Set email format to HTML
        $mail->Subject = 'Email Varification from prasadtech'; 
        $mail->Body    = "<h2>You have Registered with prasadtech</h2> 
                         <h5>Verify your email adddress to login with the below given link</h5><br/><br/>
        <a href='http://localhost/emailvarify/verify-email.php?token=$verify_token'>
        Click Me</a>";
       
        $mail->send();
        return true;
    } 
   
 if(isset($_POST['register_btn']))
  {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $verify_token = md5(rand());
    
    //email exists or not
    $check_email_query = "SELECT email FROM users WHERE email='$email' LIMIT 1";
    $check_email_query_run = mysqli_query($conn, $check_email_query);
   if(mysqli_num_rows($check_email_query_run) > 0 )
    {
         $_SESSION['status'] ="Email Id already Exists";
         header("location:register.php");
    }
    else
    {
        // insert User / Registered User Data
        $query = "INSERT INTO users(name,phone,email,password,varify_token) VALUES('$name','$phone','$email','$password','$verify_token')";
        $query_run = mysqli_query($conn, $query);

        if($query_run)
         { 
            sendemail_verify("$name","$email","$verify_token");
            $_SESSION['status'] ="Registration Successfull! please verify your Email address";
            header("location:register.php");
         }
         else
         {
            $_SESSION['status'] ="Registration Failed";
            header("location:register.php");
         }

    }
 }
?>