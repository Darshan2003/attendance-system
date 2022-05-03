<?php
session_start();
include '../function.php';
if (check_session() == false) {
    header('Location: ../index.php');
    die;
}

if(isset($_SESSION['reportCreated']))
{
    $date = date('Y-m-d');
    if($_SESSION['reportCreated'] != $date)
    {
        header('Location: mark-attendance.php');
        die;
    }
}
else
{
    header('Location: mark-attendance.php');
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
    <link rel="stylesheet" href="../styles/main.css">
    <link rel="stylesheet" href="../styles/adduser.css">
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
                        <a class="nav-link text-light selected" href="add-user.php">Add Member</a>
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
                        <a class="nav-link text-light" href="update-member.php">Update Member Details</a>
                    </li>
                </ul>
                <a href="../logout.php" class="btn btn-danger pill mb-2">Logout</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <h1 class="mb-4 text-center">Add Member</h1>
        <form action="add-user.php" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name: </label>
                <input type="text" class="form-control" id="name" name="name" maxlength="52" required>
            </div>
            <div class="mb-3 field">
                <label for="phone" class="form-label">Phone No:</label>
                <input type="text" pattern="[0-9]{10}" class="form-control" id="phone" name="phone" maxlength="10" required>
            </div>
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-success mt-4 mb-4">Add Member</button>
            </div>
        </form>
        <div class="text-center" id="info">
            <h3><span class="badge bg-danger"></span></h3>
        </div>
    </div>
    <script src="../js/index.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</body>

</html>
<?php


if ($_SERVER['REQUEST_METHOD'] == "POST") {

    include '../dbconfig.php';
    $fullname = trim($_POST['name'], " ");
    $phone = trim($_POST['phone'], " ");

    if((strlen($fullname)<52) && ((strlen($phone)===10) == true) && (is_numeric($phone) == true) && (ctype_alpha(str_replace(' ', '', $fullname)) === true))
    {

    $date = date("Y-m-d");
    $db = new PDO($dsn, $dbuser, $dbpassword);
    $q = $db->prepare("INSERT INTO `members`(`Member_Name`,`Member_Pno`,`joined`) values(:f_n,:p_n,:d);");
    $q->bindValue('f_n', $fullname);
    $q->bindValue('p_n', $phone);
    $q->bindValue('d', $date);

    if ($q->execute()) {
        $id = $db->lastInsertId();
        $q = $db->prepare("INSERT INTO `report`(`Member_ID`,`Member_Name`,`Date`) values(:id,:n,:d)");
        $q->bindValue('id', $id);
        $q->bindValue('n', $fullname);
        $q->bindValue('d', $date);
        if ($q->execute()) {
            echo "<script>displayMsg('Member Added!');</script>";
        } else {
            echo "<script> displayMsg('Member Added In database but not in attendance report!');</script>";
        }
    } else {
        echo "<script>displayMsg('Failed to add member!');</script>";
    }
    }
    else
    {
        echo "<script>displayMsg('Invalid Input Format!');</script>";
    }

}
?>