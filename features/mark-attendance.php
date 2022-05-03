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
    <link rel="stylesheet" href="../styles/main.css">
    <link rel="stylesheet" href="../styles/mark.css">
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
                        <a class="nav-link text-light selected" href="mark-attendance.php">Mark Attendance</a>
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
        <h1 class="mb-4 text-center head-title">Mark Attendance</h1>
        <div class="card pill mb-3">
            <div class="card-body text-center">
                <?php
                echo "<h3 class='date'>";
                echo date('d-m-Y');
                echo "</h3>";
                ?>
                 <div class="res-search">
                     <input class="form-control res-filter mx-auto mb-2 search pill" type="text" id="search" placeholder="Search..." aria-label="Search" onkeyup="filter();">
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include '../dbconfig.php';
                        
                        $memberCheck = true;
                        //To check for todays report
                        $date = date('Y-m-d');
                        $_SESSION['reportCreated'] = $date;
                                   
                        $result = [];
                        
                        $db = new PDO($dsn, $dbuser, $dbpassword);
                        $q = $db->prepare("SELECT `Member_ID`,`Member_Name`,`Checked_in`,`Checked_out` FROM `report` WHERE `Date`=:d");
                        $q->bindValue(':d', $date);
                        if ($q->execute()) {
                            $data = $q->fetchAll(PDO::FETCH_ASSOC);
                            
                            if (count($data) == 0) {
                                $q = $db->prepare("SELECT `Member_ID`,`Member_Name` FROM `members`");
                                $q->execute();
                                $res = $q->fetchAll(PDO::FETCH_ASSOC);
                                
                                if (count($res) > 0) {


                               
                                    $sql = "INSERT INTO `report`(`Member_ID`,`Member_Name`,`Date`) values";
                                    foreach ($res as $ele) {
                                        $M_id = $ele['Member_ID'];
                                        $M_name = $ele['Member_Name'];
                                        $sql .= "($M_id," . "'" . $M_name . "'," . "'" . $date . "'" . "),";
                                    }
                                    $sql = substr($sql, 0, -1);

                                    $q = $db->prepare($sql);
                                    
                                    if ($q->execute()) {
                                        
                                        $q = $db->prepare("SELECT `Member_ID`,`Member_Name`,`Checked_in`,`Checked_out` FROM `report` WHERE `Date`=:d");
                                        $q->bindValue(':d', $date);
                                        
                                        $q->execute();
                                        $result =  $q->fetchAll(PDO::FETCH_ASSOC);
                                    } else {
                                        echo 'ERROR';
                                    }
                                } else {
                                    $memberCheck = false;
                                }
                            } else {
                                
                                $result = $data;
                            }
                            
                            $sr = 1;
                            foreach ($result as $row) {
                                $id = $row['Member_ID'];
                        ?>
                                <tr>
                                    <th scope="row"><?php echo $sr++; ?></th>
                                    <td><?php echo $row['Member_Name'] ?></td>
                                    <td><?php echo $id ?></td>
                                    <td><?php if ($row['Checked_in'] == '00:00:00') {
                                        echo "<a href='../in_out.php?from=ma&in=$id' class='btn btn-success pill long'>In</a>";
                                    } else {
                                        echo $row['Checked_in'];
                                    }
                                    ?></td>
                                    <td><?php if ($row['Checked_out'] == '00:00:00') {
                                            echo "<a href='../in_out.php?from=ma&out=$id' class='btn btn-danger pill long'>Out</a>";
                                        } else {
                                            echo $row['Checked_out'];
                                        }
                                        ?></td>
                                </tr>
                        <?php
                            }
                        } else {
                            print "Connectivity issue";
                        }
                        ?>
                    </tbody>
                </table>
            </div> 
                
                <?php

                if($memberCheck == false)
                {
                    echo 'No Member In Database!';
                }
                ?>
               
            </div>
        </div>
    </div>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/index.js"></script>

</body>

</html>