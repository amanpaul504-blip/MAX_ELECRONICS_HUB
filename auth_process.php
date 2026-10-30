
<?php
    session_start();
    require_once 'config.php';
 

if (isset($_POST['register_btn'])) {
    $fname =$_POST['fname'];
    $lname =$_POST['lname'];
    $email =$_POST['email'];
    $password =password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check_email = $conn->query("SELECT email FROM regis_form WHERE email ='$email'");
    if ($check_email->num_rows > 0) {
        $_SESSION['alerts'][] =[
            'type' =>'error',
            'message' =>'Email is already registered!'
        ];
        $_SESSION['active_form'] ='register';
            
      } else {
        $conn->query("INSERT INTO regis_form (fname, lname, email, password) VALUES ('$fname', '$lname', '$email', '$password')");
        $_SESSION['alerts'][] =[
            'type' => 'success',
            'message' =>'Registration successful'
        ];
        $_SESSION['active_form'] ='login';
    } 

    header('Location: index.php');
    exit();}

if (isset($_POST['login_btn'])) {
    $email=$_POST['email'];
    $password =$_POST['password'];

    $result = $conn->query("SELECT *FROM regis_form WHERE email = '$email'");
    $user = $result->num_rows > 0 ? $result->fetch_assoc() :null;

    if ($user && password_verify($password, $user['password'])) {
        // clear old alerts
        unset($_SESSION['alerts']);
        unset($_SESSION['active_form']);
        
        $_SESSION['fname'] = $user['fname'];
        $_SESSION['alerts'][] = [
            'type' => 'success',
            'message' => 'Login successful'
        ];

    // Prevent POST resubmission
    header('location: index.php');
    } else {
        $_SESSION['alerts'][] = [
            'type' => 'error',
            'message' =>'Incorrect email or password!'
        ];
        $_SESSION['active_form'] = 'login';
    }

    header('location: index.php');
    
    exit();
    
}

?>  