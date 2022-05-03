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
    <link rel="stylesheet" href="../styles/view.css">
    <link rel="stylesheet" href="../styles/bd.css">
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
                        <a class="nav-link text-light selected" href="view-bd.php">View Report By Date</a>
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
        <h1 class="text-center mb-3">View Report By Date</h1>
        <form action="view-bd.php" method="get">
            <div class="d-flex justify-content-center">
                <input class="form-control input" type="date" id="date" name="date" required>
                <button class="btn btn-success bi bi-search search"> Search</button>
            </div>
        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_GET['date'])) {
                $newDate = date("d-m-Y", strtotime($_GET['date']));
                $date = $_GET['date'];

                include '../dbconfig.php';

                $db = new PDO($dsn, $dbuser, $dbpassword);
                $q = $db->prepare("SELECT `Member_ID`,`Member_Name`,`Checked_in`,`Checked_out`,`status` FROM `report` WHERE `Date`=:d");
                $q->bindValue(':d', $date);
                if ($q->execute()) {
                    $data = $q->fetchAll(PDO::FETCH_ASSOC);

                    if (count($data) > 0) {


        ?>
                        <div class="card pill mb-3 mt-5">
                            <div class="card-body text-center">
                                <?php
                                echo "<h3>";
                                echo $newDate;
                                echo "</h3>";
                                ?>
                                <div class="res-search">
                                    <input class="form-control res-filter mx-auto mb-2 filter pill" type="text" id="search" placeholder="Search..." aria-label="Search" onkeyup="filter('status');">
                                </div>
                                <div class="res-table">

                                    <table class="table table-dark table-striped table-hover table-responsive table-bordered " id="myTable">
                                        <thead>
                                        <tr>
                                            <th scope="col">Sr.</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">ID</th>
                                            <th scope="col">Checked In</th>
                                            <th scope="col">Checked Out</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

$sr = 1;
                                        foreach ($data as $row) {

                                            ?>
                                            <tr>
                                                <th scope="row"><?php echo $sr++; ?></< /th>
                                                <td><?php echo $row['Member_Name']; ?></< /td>
                                                <td><?php echo $row['Member_ID']; ?></td>
                                                <td><?php echo $row['Checked_in']; ?></td>
                                                <td><?php echo $row['Checked_out']; ?></td>
                                                <td><?php echo $row['status']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                
                            </div>
                                <button class='btn btn-success bi bi-file-earmark-spreadsheet export' onclick='exportExcel(`date-report.csv`)' > Export</button>
                                <button class='btn btn-warning bi bi-printer print' onclick='window.print();' > Print</button>
                                </div>
                            </div>
                            <?php
                                        
                                    } else {
                                        echo "<p class='text-center h3 mt-5'>";
                                        echo "No data of $newDate";
                                        echo "</p>";
                                    }
                                }
                                else
                                {
                                    echo "Connectivity issue";
                                }
                            }
                        }
            ?>
    </div>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/index.js"></script>
    <script src="../js/exportToExcel.js"></script>
</body>

</html>