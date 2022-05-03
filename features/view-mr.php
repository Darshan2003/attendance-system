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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../styles/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/main.css">
    <link rel="stylesheet" href="../styles/view.css">
    <title>Attendance System</title>
</head>

<body class="features">
    <nav class="navbar navbar-dark bg-dark ">
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
                        <a class="nav-link text-light selected" href="view-mr.php">View Member Report</a>
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
    <div class="container" id="view-mr">
        <h1 class="text-center mb-3">View Member Report</h1>
        <form action="view-mr.php" method="get">
            <div class="d-flex justify-content-center">
                <input class="form-control input change-input" type="text" id="search" placeholder="Enter Member ID" name="id" required>
                <button class="btn btn-success bi bi-search search"> Search</button>
            </div>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_GET['id'])) {
                include '../dbconfig.php';
                $check = false;
                $isMember = false;
                $id = $_GET['id'];
                $db = new PDO($dsn, $dbuser, $dbpassword);
                $q = $db->prepare("SELECT `Member_Name`,`joined`,`Member_Pno` FROM `members` WHERE `Member_ID`=:id");
                $q->bindValue('id', $id);
                if ($q->execute()) {
                    $data = $q->fetchAll(PDO::FETCH_ASSOC);
                    if (count($data) > 0) {

                        $Mname = $data[0]['Member_Name'];
                        $Pno = $data[0]['Member_Pno'];
                        $joined = $data[0]['joined'];
                        $isMember = true;
                    }
                    $q = $db->prepare("SELECT `Date`,`Checked_in`,`Checked_out`,`status` FROM `report` WHERE `Member_ID`=:id");
                    $q->bindValue(':id', $id);

                    if ($q->execute()) {
                        $data = $q->fetchAll(PDO::FETCH_ASSOC);


                        if (count($data) > 0) {
                            $check = true;
                            $status = array_count_values(array_column($data, 'status'));
                            $absents = isset($status['ABSENT']) ? $status['ABSENT'] : 0;
                            $presents = isset($status['PRESENT']) ? $status['PRESENT'] : 0;

        ?>
                            <div class="card pill mb-3 mt-5">
                                <div class="card-body">
                                    <div>
                                        <?php
                                        if ($isMember == false) {
                                            echo "<b>This Member is not in database</b>";
                                        }
                                        ?>
                                        <div class="row">
                                            <?php
                                            if ($isMember == true) {


                                            ?>
                                                <div class="col">
                                                    <h5>Member Name: <?php echo $Mname ?></h5>
                                                </div>
                                            <?php } ?>
                                            <div class="col">
                                                <h5>Member ID: <?php echo $id ?></h5>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <h5>No. Of days Present: <?php echo $presents ?></h5>
                                            </div>
                                            <div class="col">
                                                <h5>No. of days Absent: <?php echo $absents ?></h5>
                                            </div>
                                        </div>
                                        <?php if ($isMember == true) {
                                        ?>
                                            <h5>Phone No: <?php echo $Pno ?></h5>
                                            <h5>Joined At: <?php echo $joined ?></h5>
                                        <?php } ?>


                                    </div>
                            <?php
                        } else {

                            echo "<p class='text-center h3 mt-5'>";
                            echo 'No such Member';
                            echo "</p>";
                        }
                    }
                } else {
                    echo "Error";
                }
                if ($check === true) {


                            ?>
                            
                            <div class="res-table">

                                <table class="table table-dark table-striped table-hover table-responsive table-bordered mt-4 " id="myTable">
                                    <thead>
                                        <tr>
                                            <th scope="col">Sr.</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Checked In</th>
                                            <th scope="col">Checked Out</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $date = date('Y-m-d');
                                        $sr = 1;
                                        foreach ($data as $row) {
                                        ?>
                                            <tr>
                                                <th scope="row"><?php echo $sr++ ?></th>
                                                <td><?php echo $row['Date'] ?></td>
                                                <td><?php if (($row['Checked_in'] == '00:00:00') && $row['Date'] == $date) {
                                                        echo "<a href='../in_out.php?from=v-mr&in=$id' class='btn btn-success pill long'>In</a>";
                                                    } else {
                                                        echo $row['Checked_in'];
                                                    }
                                                    ?></td>
                                                <td><?php if (($row['Checked_out'] == '00:00:00') && ($row['Date'] == $date)) {
                                                        echo "<a href='../in_out.php?from=v-mr&out=$id' class='btn btn-danger pill long'>Out</a>";
                                                    } else {
                                                        echo $row['Checked_out'];
                                                    }
                                                    ?></td>
                                                <td><?php echo $row['status'] ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>


                            <button class='btn btn-success bi bi-file-earmark-spreadsheet export' onclick='exportExcel(`member-report.csv`)'> Export</button>
                            <button class='btn btn-warning bi bi-printer print' onclick='window.print();'> Print</button>
                        <?php } ?>

                <?php
            }
        }

                ?>
                                </div>
                            </div>
    </div>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/index.js"></script>
    <script src="../js/exportToExcel.js"></script>
</body>

</html>