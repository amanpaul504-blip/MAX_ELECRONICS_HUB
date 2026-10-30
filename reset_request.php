<?php  
date_default_timezone_set('UTC');
session_start();

use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception;  

require 'PHPMailer/src/Exception.php'; 
require 'PHPMailer/src/PHPMailer.php'; 
require 'PHPMailer/src/SMTP.php';  

// 1. Load configuration pathway map
require_once 'C:/xampp/htdocs/MAX_ELECTRONICS_HUB/appl/conf.php';

if (!file_exists($autoloader)) {
    die("Configuration Error: Composer autoloader not found. Run 'composer install' in the project directory.");
}
require_once $autoloader;

// 2. ISOLATED DOTENV ENVIRONMENT INITIALIZATION
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    
    $dotenv->required([
        'CIHL_MAIL_USER', 
        'CIHL_MAIL_PASSWORD', 
        'CIHL_PAYPAL_URL', 
        'CIHL_CREDIT_CARD_URL',
    ])->notEmpty();
} catch (\Exception $e) {
    die("Critical Configuration Error: Missing or invalid environment keys. Detail: " . $e->getMessage());
}

if (isset($_POST['reset_request_submit'])) {          
    // 1. Generate secure tokens in hexadecimal format     
    $selector = bin2hex(random_bytes(16));  // 32-character hexadecimal string
    $validator = bin2hex(random_bytes(32)); // 64-character hexadecimal string
    
    // 2. Hash the validator securely for the database
    $validatorHash = password_hash($validator, PASSWORD_DEFAULT);

    // 3. Construct the clean URL (Hexadecimal characters are naturally URL-safe)
    $url = "http://localhost/MAX_ELECTRONICS_HUB/res/create_new_password.php?selector=" . $selector . "&validator=" . $validator;      

    // 4. Set expiration (15 minutes from now)     
    $expires = date("Y-m-d H:i:s", time() + 900); 
    
    require 'C:/xampp/htdocs/MAX_ELECTRONICS_HUB/res/includes/dB_pwd.php';         

    $userEmail = isset($_POST['email']) ? trim($_POST['email']) : "";      

    // Check if email is empty before proceeding     
    if (empty($userEmail)) {         
        header("Location: ../reset_password.php?error=emptyemail");         
        exit();     
    }

     // 1. Check whether email exists     
    $sql = "SELECT email FROM regis_form WHERE email = ?;"; 
    $stmt = mysqli_stmt_init($conn);      

    if (!mysqli_stmt_prepare($stmt, $sql)) {         
        header("Location: ../reset_password.php?error=sqlerror");
        exit();
    } else {         
        mysqli_stmt_bind_param($stmt, "s", $userEmail);         
        mysqli_stmt_execute($stmt);                  
        $result = mysqli_stmt_get_result($stmt); 

    if (mysqli_num_rows($result) == 0) {
        echo <<<HTML
        <link href='https://cdn.boxicons.com/3.0.6/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>
            <link href='https://cdn.boxicons.com/3.0.6/fonts/basic/boxicons.min.css' rel='stylesheet'>
        <link href='unpkg.com' rel='stylesheet'>
        <style>
            .error-wrapper{
            display:flex;
            justify-content:center;
            align-items:center;
            height:80vh;
            font-family:Arial,sans-serif;
            }

            .error-card {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #ffebee; /* Light red background */
            color: #c62828;            /* Dark red text */
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid #ffcdd2;
            width: 300px;
            padding:40px;

            }
            /* Icon styling */
            .error-card i {
            font-size: 30px;
            display: block;
            margin-bottom: 10px;
            }

            .error-card button {
            background-color: #c62828;
            color: white;
            border: none;
            padding: 5px 15px;
            font-size: 14px;
            border-radius: 50px;
            cursor: pointer;
            margin-top: 0px;
            transition: 0.3s;
            }

            .error-card button:hover {
                background-color: #b71c1c;
                transform: scale(1.05);
            }
            .error-card h3 { margin: 0; font-size: 1.0rem; }
            .error-card p { color: #555; margin-top: 0px; font-size:1.0rem; }
        </style>


        <div class="error-card">
             <!-- Error icon (X circle) -->
            <i class='bx bx-x-circle'></i>
            <h3>Error...</h3>
            <p>Failed to reload your account</p>
            <strong>Wait a moment, then try again...</strong><br><br>
            <button onclick="window.location.href='http://localhost/MAX_ELECTRONICS_HUB/res/foget.php'">Ok</button>
        </div>
        HTML;
        exit();
        }                 

        // Security Best Practice: Don't enumerate accounts.              
        // Pretend it succeeded so attackers don't know which emails are registered.             
        if (!$row = mysqli_fetch_assoc($result)) {             
            header("Location: ../reset_password.php?reset=success");             
            exit();         
        }     
    }       
  
     // 2. Delete any existing tokens for this email to prevent clutter     
        $sql = "DELETE FROM pwdreset WHERE pwdResetEmail=?";     
        $stmt = mysqli_stmt_init($conn);     
        if (!mysqli_stmt_prepare($stmt, $sql)) {         
            header("Location: ../reset_password.php?error=sqlerror");
            exit();       
        } else {         
            // FIXED: Changed undefined $mail to $userEmail
            mysqli_stmt_bind_param($stmt, "s", $userEmail);         
            mysqli_stmt_execute($stmt);       
        }      

    // 3. Insert the new reset token     
       $sql = "INSERT INTO pwdreset (pwdResetEmail, selector, validator, pwdResetExpires) VALUES (?, ?, ?, ?);";     
       $stmt = mysqli_stmt_init($conn);   

       if (!mysqli_stmt_prepare($stmt, $sql)) {         
            header("Location: ../reset_password.php?error=sqlerror");
            exit();     
        } else {         
            // Hash the token for security before storing         
            $validatorHarsh = password_hash($validator, PASSWORD_DEFAULT);     

            mysqli_stmt_bind_param($stmt, "ssss",  $userEmail, $selector, $validatorHarsh, $expires);         
            mysqli_stmt_execute($stmt);     
        }      
 
        mysqli_stmt_close($stmt);     
        mysqli_close($conn);       

        $subject  = 'Reset your password for cihl';          
        $message  = '<p>We received a password reset request, but if you did not make this request ignore this email,</p>';     
        $message .= '<p>Click the button bolow to reset a password:<br> ';     
        $message .= '<p><a href="' . $url . '" style="background-color: #87CEEB; color: #ffffff; padding: 5px 20px; text-decoration: none; border-radius: 5px; display: inline-block; font-family: Arial, sans-serif;">Reset Your Password</a></p>';



    // 4. Send the email      
        $mail = new PHPMailer(true);      

    try {      
        $mail->isSMTP();     
        $mail->Host       = 'smtp.gmail.com';     
        $mail->SMTPAuth   = true;     
        $mail->Username = $_ENV['CIHL_MAIL_USER'];    
        $mail->Password = $_ENV['CIHL_MAIL_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     
        $mail->Port       = 587;    

        $mail->setFrom('initiativescommunity6@gmail.com', 'CIHL');     
        $mail->addAddress($userEmail);      

        $mail->isHTML(true);     
        $mail->Subject = $subject;     
        $mail->Body    = $message;     
        $mail->AltBody = "Reset your password using the following link:\n\n".$url;      

        $mail->send();      

        header("Location: ../create_new_password.php?reset=success");     
        exit();      

        } catch (Exception $e) {  

        echo <<<HTML
        <link href='https://cdn.boxicons.com/3.0.6/fonts/basic/boxicons.min.css' rel='stylesheet'>
        <style>
        .error-wrapper{
        display:flex;
        justify-content:center;
        align-items:center;
        height:80vh;
        font-family:Arial,sans-serif;
        }
        .error-card{
        background:#ffebee;
        padding:40px;
        border-radius:12px;
        text-align:center;
        box-shadow:0 5px 15px rgba(0,0,0,.2);
        }
        .error-card i{
        font-size:60px;
        color:red;
        }
        </style>

        <div class="error-wrapper">
        <div class="error-card">
        <i class='bx bx-x-circle'></i>
           <h3>Error!</h3>
        <p>Failed to reset a password.<br> 
        <strong>Check your internet connection...</strong></p>
        </div>
        </div>

        <script>
        setTimeout(function(){
        window.location.href="http://localhost/MAX_ELECTRONICS_HUB";
        },3000);
        </script>
        HTML;

        echo <<<HTML
            <link href='https://cdn.boxicons.com/3.0.6/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>
            <link href='https://cdn.boxicons.com/3.0.6/fonts/basic/boxicons.min.css' rel='stylesheet'>
            <link href='unpkg.com' rel='stylesheet'>

            <style>
            .error-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            .error-card {
            background-color: #ffebee; /* Light red background */
            color: #c62828;            /* Dark red text */
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid #ffcdd2;
            max-width: 500px;
            }
            /* Icon styling */
            .error-card i {
            font-size: 4rem;
            display: block;
            margin-bottom: 15px;
            }
            .error-card h3 { margin: 0; font-size: 1.5rem; }
            .error-card p { color: #555; margin-top: 10px; }
            
            /* Retry Button (Optional but helpful for errors) */
            .retry-btn {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            background-color: #c62828;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            }
            </style>

            <div class="error-wrapper">
            <div class="error-card">
            <i class='bx bx-x-circle'></i>
                    <h3>Failed to send email</h3>
                    <p>Error details: {$mail->ErrorInfo}</p>
                    <p><strong>Redirecting back to home page...</strong></p>
            </div>
            </div>

            <script>
            setTimeout(function() {
            window.location.href = "http://localhost/MAX_ELECTRONICS_HUB";
            }, 5000);
            </script>
            HTML;
            exit();
            }
            } else {     
            header("Location: ../index.php");     
            exit();     
            }