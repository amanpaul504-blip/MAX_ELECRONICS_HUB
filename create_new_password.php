<?php
require "header.php";
?>

<main>
    <div class="wrapper-main">
        <section class="section-default">
         <?php
            $selector = $_GET["selector"] ?? '';
            $validator = $_GET["validator"] ?? '';

            if (empty($selector) && empty($validator)) {
                echo <<<HTML
    <link href='https://cdn.boxicons.com/3.0.6/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>
        <link href='https://cdn.boxicons.com/3.0.6/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='unpkg.com' rel='stylesheet'>
    
    <style>
        .success-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .success-card {
            background-color: #e0f2f1;
            color: #2e7d32;
            padding: 60px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid #a5d6a7;
            max-width: 700px;
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
            <p>Your request has been submitted.<br> 
            <strong>Check your email for reset link...</strong></p>
        </div>
    </div>

    <script>
        setTimeout(function() {
        window.location.href = 'http://localhost/MAX_ELECTRONICS_HUB/';
        }, 5000);  // 5000 milliseconds = 5 seconds
    </script>
    HTML;
            } else {
                // Ensure tokens are hex and prevent logic errors
                if (ctype_xdigit($selector) === false || ctype_xdigit($validator) === false) {
                    ?>

                    <!-- Close PHP to write HTML safely -->
                    <form action="includes/reset_request.php" method="POST">
                        <input type="hidden" name="selector" value="<?php echo htmlspecialchars($selector); ?>">
                        <input type="hidden" name="validator" value="<?php echo htmlspecialchars($validator); ?>">
                        <input type="password" name="pwd" placeholder="Enter a new password..." required>
                        <input type="password" name="pwd-repeat" placeholder="Repeat new password..." required>
                        <button type="submit" name="reset_request_submit">Reset password</button>
                    </form>
                    <?php
                } else {
                    echo "Invalid request tokens.";
                }
            }
         ?>
        </section>
    </div>
</main>

<?php
require "footer.php";
?>
