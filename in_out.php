<?php

include 'dbconfig.php';

$db = new PDO($dsn, $dbuser, $dbpassword);
date_default_timezone_set('Asia/Kolkata');
$time=date("H:i:s");
$date=date("Y-m-d");
if(isset($_GET['in']))
{
    $id = $_GET['in'];
    $q = $db->prepare("UPDATE `report` SET `Checked_in`=:t,`status`=:p  WHERE `Member_ID`=:id AND `Date`=:d");
    $q->bindValue('t',$time);
    $q->bindValue('id',$id);
    $q->bindValue('d',$date);
    $q->bindValue('p','PRESENT');
    if(!$q->execute())
    {
        echo "<script>alert('Cannot Update Checked In!')</script>";
    }
}
if (isset($_GET['out'])) {
    $id = $_GET['out'];
    $q = $db->prepare("UPDATE `report` SET `Checked_out`=:t,`status`=:p  WHERE `Member_ID`=:id AND `Date`=:d");
    $q->bindValue('t',$time);
    $q->bindValue('id',$id);
    $q->bindValue('d',$date);
    $q->bindValue('p','PRESENT');
    if(!$q->execute())
    {
        echo "<script>alert('Cannot Update Checked !')</script>";
    }
}
if(isset($_GET['from']))
{
    if($_GET['from']=='ma')
    {
        header('Location: features/mark-attendance.php');
    }
    else if($_GET['from'] == 'v-mr' )
    {
        $id = isset($_GET['in'])? $_GET['in']:$_GET['out'];

        header("Location: features/view-mr.php?id=$id");
    }
}
?>