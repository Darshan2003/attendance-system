<?php
session_start();
include '../function.php';
if (check_session() == false) {
    header('Location: ../index.php');
    die;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../styles/main.css">
    <link rel="stylesheet" href="../styles/update-m.css">
    <title>Attendance System</title>
</head>

<body class="features">
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <div class="navbar-brand">
                <span><img src="../Images/logo.png" alt="logo" class="rounded-circle xs-logo"></span>
                <span class="navbar-brand"><b>Attendance</b></span>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse collapsing text-center" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-light" href="add-user.php">Add Member</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="mark-attendance.php">Mark Attendance</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="view-mr.php">View Member Report</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="members.php">View Members</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="view-bd.php">View Report By Date</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light selected" href="update-member.php">Update Member Details</a>
                    </li>
                </ul>
                <a href="../logout.php" class="btn btn-danger pill mb-2">Logout</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <h1 class="mb-4 text-center">Update Member</h1>
        <div class="card pill mb-3">
            <div class="card-body ">
                <form action="update-member.php" method="POST">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Enter Member ID: </span>
                        <input type="text" class="form-control" name="id" placeholder="id..." aria-label="Member ID" aria-describedby="basic-addon1" required>
                    </div>

                    <div class="mb-3 mt-5">
                        <label for="fullname" class="form-label"><b>Enter Updated Full Name: </b></label>
                        <input type="text" class="form-control bg-dark text-light" id="name" name="name" maxlength="52" placeholder="name here..." required>
                    </div>
                    <div class="mb-3 field">
                        <label for="phone" class="form-label"><b>Enter Updated Phone no: </b></label>
                        <input type="text" pattern="[0-9]{10}" class="form-control bg-dark text-light" id="phone" name="phone" maxlength="10" placeholder="phone no. here..." required>
                    </div>
                    <button class="btn btn-success">Update <i class="bi bi-arrow-right"></i></button>
                </form>
            </div>
        </div>
        <div class="text-center" id="info">
            <h3><span class="badge bg-danger"></span></h3>
        </div>
    </div>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/index.js"></script>
</body>

</html>

<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    include '../dbconfig.php';
    $fullname = trim($_POST['name'], " ");
    $phone = trim($_POST['phone'], " ");
    $id = $_POST['id'];
    if((strlen($fullname)<52) && ((strlen($phone)===10) == true) && (is_numeric($phone) == true) && (ctype_alpha(str_replace(' ', '', $fullname)) === true))
    {

    $db = new PDO($dsn, $dbuser, $dbpassword);
    $q = $db->prepare("UPDATE `members`,`report` SET `members`.`Member_Name`=:n,`members`.`Member_Pno`=:p,`report`.`Member_Name`=:n WHERE `members`.`Member_ID` =:id AND `report`.`Member_ID` =:id");
    $q->bindValue('n', $fullname);
    $q->bindValue('p', $phone);
    $q->bindValue('id', $id);
    $q->execute();
    if ($q->rowCount()>0) {
        echo "<script>displayMsg('Information Updated')</script>";
    }
    else
    {
        echo "<script>displayMsg('Failed to Update')</script>";
    }

    }
    else
    {
        echo "<script>displayMsg('Invalid Input Format!');</script>";
    }

}


?>