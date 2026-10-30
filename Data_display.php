<?php
session_start()

$servername = 'localhost';
$username = 'root';
$password = ''; 
$dbname = 'appl_db';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <title>Data display template</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 10px;
            margin: 5px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container-fluid p-5 bg-primary text-white text -center">
        <h1>Data table from Applicants</h1>
    </div>

    <div class="container mt-5">
        <div class="row">
    
    <table class="table">
        <thead>
         <tr>
            <th>ID</th><th>firstname</th><th>lastname</th><th>DoB</th><th>Sex</th><th>height</th><th>nationality</th><th>pnumber</th> <th>email</th><th>pnumber_a</th><th>hnumber</th><th>street</th> <th>t_city</th><th>country</th><th>cu_study</th><th>country_a</th><th>c-studied</th><th>yenrolled</th><th>sID_num</th><th>d_graduated</th><th>n_referee</th><th>n_univ</th><th>country_b</th><th>pnumber_b</th><th>email_b</th><th>fapplied</th><th>duration</th><th>d_start</th><th>uploadedFile1</th><th>uploadedFile2</th><th>uploadedFile3</th><th>uploadedFile4</th><th>Time_uploaded</th>
         </tr>
         </thead>
    <tbody>

    <?php
        $sql="SELECT * FROM `appl_form`";
        $run =mysqli_query($conn, $sql);

        while($row = mysqli_fetch_array($run)) {
    ?>

    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['firstname']; ?></td>
        <td><?php echo $row['lastname']; ?></td>
        <td><?php echo $row['DoB']; ?></td>
        <td><?php echo $row['sex']; ?></td>
        <td><?php echo $row['height']; ?></td>
        <td><?php echo $row['nationality']; ?></td>
        <td><?php echo $row['pnumber']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td><?php echo $row['pnumber_a']; ?></td>
        <td><?php echo $row['hnumber']; ?></td>
        <td><?php echo $row['street']; ?></td>
        <td><?php echo $row['t_city']; ?></td>
        <td><?php echo $row['country']; ?></td>
        <td><?php echo $row['cu_study']; ?></td>
        <td><?php echo $row['country_a']; ?></td>
        <td><?php echo $row['c_studied']; ?></td>
        <td><?php echo $row['yenrolled']; ?></td>
        <td><?php echo $row['sID_num']; ?></td>
        <td><?php echo $row['d_graduated']; ?></td>
        <td><?php echo $row['n_referee']; ?></td>
        <td><?php echo $row['n_univ']; ?></td>
        <td><?php echo $row['country_b']; ?></td>
        <td><?php echo $row['pnumber_b']; ?></td>
        <td><?php echo $row['email_b']; ?></td>
        <td><?php echo $row['fapplied']; ?></td>
        <td><?php echo $row['duration']; ?></td>
        <td><?php echo $row['d_start']; ?></td>
        <td><?php echo $row['file_path']; ?></td>
        <td><?php echo $row['file_path1']; ?></td>
        <td><?php echo $row['file_path2']; ?></td>
        <td><?php echo $row['file_path3']; ?></td>
        <td><?php echo $row['Time_uploaded']; ?></td>
    </tr>

<?php } //while ends here?>


</body>
</html>
