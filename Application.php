<?php
session_unset();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<script type="text/javascript">
  window.history.foward(); 
</script>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Home</title>
  <link rel="stylesheet" href="app.css">
  <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
</head>


<style>
 
@import url('https://fonts.googleapis.com/css?family=Poppins:400,700,900');
body{
  background-color: #87CEEB;
}

.application-form {
  display: grid;
  /* Defines two columns: one for labels (auto-sized to content width) 
     and one for inputs (1fr takes the remaining available space)  grid-template-columns: auto 1fr;*/ 
  grid-template-columns: repeat(1, 1fr);
  padding: 0px 50px 0px 50px;
  align-items: center;
  justify-content: center;
  margin: 10px 30px 30px 30px;
  background-color: #0487E2;
  border-radius: 15px;
}

a {
  text-decoration: none;
  font-size: 22px;
  line-height: 1.5;
  color: #87CEEB;
}

a:hover {
  color: #fff;
  font-size: 24px;
}

li {
  font-size: 24px;
  color: #87CEEB;
}

li:hover {
  color: #fff;
}

p {
  font-size: 20px;
  margin-bottom: 10px;
  margin-top: 100px;
}

</style>
<body>
    <p align="center">Select the right category you want to apply, then fill the form with correct details as per instructions on each field </p>
</body>

<body>
<form class="application-form">
<ul><li><a href="appl/int.php">Application form for internship program (Safari Internship Program) </a><br></li>

<li><a href="appl/profv.php">Application for a visit to Tanzania to provide medical service as a medical practitioner or specialist</a><br></li> 

<li><a href="appl/don.php">Pledge donation of fund to CIHL program</a><br></li></ul>
</form>

<script src="MAX_ELECTRONICS_HUB/script.js"></script>
</body>
</html>