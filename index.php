<?php

session_start();

include 'function.php';
if(check_session()==true)
{
    header('Location: features/mark-attendance.php');
    die;
}
//To check if the there is some message to display
$display = false;
$displayMsg = "";

//to use $conn
include 'dbconfig.php';
$dsn = "mysql:host=$dbhost;dbname=$dbname";

//if request method is post
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = trim($_POST['username'], " ");
    $password = trim($_POST['password'], " ");
    
    //if password and email is not empty
    if (!empty($username) && !empty($password)) {
        
        $db = new PDO($dsn, $dbuser, $dbpassword);
        $q = $db->prepare("SELECT * FROM `supervisor` WHERE `username` = :u_n AND `password` = :p");
        $q->bindValue('u_n', $username);
        $q->bindValue('p', $password);
        $q->execute();
        
        if($q->rowCount()>0)
        { 
            $_SESSION['isLogged'] = 'true';
            header("Location: features/mark-attendance.php");
            die;
        }
        else{
            $display = true;
            $displayMsg = "Invalid Credentials!";

        }
    } else {
        $display = true;
        $displayMsg = "Username or Password Cannot be empty!";
       // echo "Username or Password cannot be empty";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/login.css">
    <title>Login</title>
</head>

<body>
    <div class="card mx-auto shadow-lg text-light border-0 card-custom align-items-center" >
            <form action="index.php" method="post">
            
            <div class="card-body card-b-custom mt-3">  
            <div class="cardHead d-flex justify-content-between">
                <h1 class="card-title">Login</h1>          
                <img src="Images/logo.png" alt="logo" class="rounded-circle sm-logo">
            </div>
            <hr>    
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" maxlength="15" required>
                </div>
                <div class="mb-3 field">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" maxlength="15" required>
                    <i class="bi bi-eye-slash" id="togglePassword" onclick="toggleEye();"></i>
                </div>
                
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-warning mt-4 mb-4 ">Login</button>
                <?php
                if($display=true)
                {
                    echo "<p style='color:#f3ca20'>";
                    echo $displayMsg;
                    echo "</p>";
                }
                ?>
                </div>
                
            </div>
        </form>
    </div>
    <script src="js/index.js"></script>
</body>

</html>