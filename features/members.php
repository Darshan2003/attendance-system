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
    <link rel="stylesheet" href="../styles/member.css">
    
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
                        <a class="nav-link text-light selected" href="members.php">View Members</a>
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
        <h1 class="mb-4 text-center">Members</h1>
        <div class="card pill mb-3">
            <div class="card-body text-center">


                <?php

                include '../dbconfig.php';

                $db = new PDO($dsn, $dbuser, $dbpassword);
                $q = $db->prepare("SELECT * FROM `members`");
                if ($q->execute()) {
                    $data = $q->fetchAll(PDO::FETCH_ASSOC);
                    if (count($data) > 0) {
                ?>
                        <div class="res-search">
                            <input class="form-control res-filter search mx-auto mb-2 pill mb-3" type="text" id="search" placeholder="Search..." aria-label="Search" onkeyup="filter();">
                        </div>
                        <div class="res-table">

                            <table class="table table-dark table-striped table-hover table-responsive table-bordered " id="myTable">
                                <thead>
                                    <tr>
                                        <th scope="col">Sr.</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">ID</th>
                                        <th scope="col">Phone No.</th>
                                        <th scope="col">Joined On</th>
                                        <th scope="col">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    $sr = 1;

                                    foreach ($data as $row) {
                                        $id = $row['Member_ID'];
                                    ?>
                                        <tr>
                                            <th scope="row"><?php echo $sr++; ?></th>
                                            <td><?php echo $row['Member_Name']; ?></td>
                                            <td><?php echo $id; ?></td>
                                            <td><?php echo $row['Member_Pno']; ?></td>
                                            <td><?php echo $row['joined']; ?></td>                                                             
                                            <!-- return confirm(`Are you sure? You Want Delete this member?`) -->
                                            <td><?php echo "<a href='delete-member.php?from=m&id=$id' class='btn btn-danger pill bi bi-trash  custom-confirm-link' data-confirmation='Are you sure you want to delete this member?'> Delete</a>" ?></td>
                                        </tr>

                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>
                        <button class='btn btn-success bi bi-file-earmark-spreadsheet export' onclick='exportExcel(`members.csv`)'> Export</button>
                        <button class='btn btn-warning bi bi-printer print' onclick='window.print();'> Print</button>
                <?php


                    } else {
                        echo "<p class='text-center mt-4 h3'>";
                        echo 'No member in database';
                        echo "</p>";
                    }
                } else {
                    echo 'Some error occured';
                }
                ?>


            </div>
        </div>

    </div>
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/index.js"></script>
    <script src="../js/exportToExcel.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js" integrity="sha512-RdSPYh1WA6BF0RhpisYJVYkOyTzK4HwofJ3Q7ivt/jkpW6Vc8AurL1R+4AUcvn9IwEKAPm/fk7qFZW3OuiUDeg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript">
        //  click event listener to links that requires custom confirm
        $('.custom-confirm-link').click(function(e) {
            
            //  get link and its confirmation message
            var link = $(this);
            var confirmationmessage = link.data('confirmation');
            var address = link.attr('href');

            e.preventDefault();

            bootbox.confirm({
                title: 'Confirm',
                closeButton: false,
                message: confirmationmessage,
                buttons: {
                    confirm: {
                        label: 'Delete',
                        className: 'btn-danger rounded'
                    },
                    cancel: {
                        label: 'Cancel',
                        className: 'btn-secondary rounded'
                    }
                },
                callback: function(result) {
                    if (result) {
                        //  simulate click the link
                        window.location.href = address;
                    }
                }
            });
        });
    </script>

</body>

</html>