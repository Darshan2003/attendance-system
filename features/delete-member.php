<?php

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
        include '../dbconfig.php';
        if(isset($_GET['id']))
        {
            
            $id = $_GET['id'];
            $db = new PDO($dsn, $dbuser, $dbpassword);
            $q = $db->prepare("DELETE FROM `members` WHERE `members`.`Member_ID` = :id");
            $q->bindValue('id', $id);
            if($q->execute())
            {
                if($_GET['from'] === 'm')
                {
                    header('Location: members.php');
                }
            }
            else
            {
                echo "Some Error Occured";
            }
        }
    }

?>