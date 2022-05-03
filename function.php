<?php

function check_session()
{
    if ((isset($_SESSION['isLogged']))) {
        return true;
    }
    else
    {
        return false;
    }
}
?>