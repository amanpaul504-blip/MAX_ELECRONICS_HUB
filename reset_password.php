<?php
if (isset($_POST['reset-password-submit'])) {

    // 1. Gather form inputs
    $selector = isset($_POST['selector']) ? trim($_POST['selector']) : "";
    $validator = isset($_POST['validator']) ? trim($_POST['validator']) : "";
    $password = $_POST['pwd'] ?? "";
    $passwordRepeat = $_POST['pwd-repeat'] ?? "";

    // 2. Basic validation checks
    if (empty($selector) || empty($validator) || empty($password) || empty($passwordRepeat)) {
        header("Location: ../create_new_password.php?error=emptyfields&selector=" . urlencode($selector) . "&validator=" . urlencode($validator));
        exit();
    }

    if ($password !== $passwordRepeat) {
        header("Location: ../create_new_password.php?error=passwordmismatch&selector=" . urlencode($selector) . "&validator=" . urlencode($validator));
        exit();
    }

    if (!ctype_xdigit($selector) || !ctype_xdigit($validator)) {
        header("Location: ../reset_password.php?error=invalidtoken");
        exit();
    }

    // 3. Establish database connection
    require 'dB_pwd.php'; 

    // 4. Look up token details and check expiry
    $current_time = date('Y-m-d H:i:s');
    $sql = "SELECT * FROM pwdreset WHERE selector=? AND pwdResetExpires>=? LIMIT 1;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../reset_password.php?error=sqlerror");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $selector, $current_time);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$row = mysqli_fetch_assoc($result)) {
    echo "<script>alert('This link has expired or is invalid'); window.location.href='http://localhost/MAX_ELECTRONICS_HUB/res/foget.php';</script>";
    }


    // 5. Verify the raw hexadecimal validator against the database hash
    $tokenEmail = $row['pwdResetEmail'];
    $tokenCheck = password_verify($validator, $row['validator']);

    if ($tokenCheck === false) {
        header("Location: ../reset_password.php?error=tokeninvalid");
        exit();
    }

    // 6. Check if the user email actually exists in the main user table
    $sql = "SELECT * FROM regis_form WHERE email=?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../reset_password.php?error=sqlerror");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$userRow = mysqli_fetch_assoc($result)) {
        header("Location: ../reset_password.php?error=nouser");
        exit();
    }

    // 7. Update the password in your main user table
    $sql = "UPDATE regis_form SET password=? WHERE email=?;"; // Make sure column name 'password' matches your DB schema
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../reset_password.php?error=sqlerror");
        exit();
    }

    // Securely hash the new user password
    $newPasswordHash = password_hash($password, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "ss", $newPasswordHash, $tokenEmail);
    mysqli_stmt_execute($stmt);

    // 8. Delete the used token from the pwdreset table to prevent reuse
    $sql = "DELETE FROM pwdreset WHERE pwdResetEmail=?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../reset_password.php?error=sqlerror");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
    mysqli_stmt_execute($stmt);

    // 9. Success! Redirect to login page
    echo <<<HTML
        <!-- Move meta tags and external stylesheets to the real document head or leave them grouped securely -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <link href='https://cdn.boxicons.com/3.0.6/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>
        <link href='https://cdn.boxicons.com/3.0.6/fonts/basic/boxicons.min.css' rel='stylesheet'>

        <style>
        /* Page Layout Framework */
        .wrapper-main {
            margin: 0;
            padding: 0;
            background: #f4f6f9;
            font-family: Arial, Helvetica, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
        }

        /* Success Card Styling */
        .success-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 60vh;
        }
        .success-card {
            background-color: #e0f2f1;
            color: #2e7d32;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid #a5d6a7;
            max-width: 500px;
        }
        .success-card i {
            font-size: 4rem;
            display: block;
            margin-bottom: 15px;
        }
        .success-card h3 { margin: 0; font-size: 1.5rem; }
        .success-card p { color: #555; margin-top: 10px; }
        </style>

        <div class="success-wrapper">
            <div class="success-card">
                <i class='bx bx-check-circle'></i>
                <h3>Success!</h3>
                <p>Your password has been reset successfully....<br> 
                <strong> Redirecting to home page...</strong></p>
            </div>
        </div>
        <script>
            setTimeout(function() {
                window.location.href = 'http://localhost/MAX_ELECTRONICS_HUB/';
            }, 5000); // Redirects in 5 seconds
        </script>
        HTML;

    }
