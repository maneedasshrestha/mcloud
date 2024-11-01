<?php

function loggedin_only()
{
    if ($_SESSION['loggedin'] !== true) {
        header("Location: login.php");
    }
}

function logout()
{
    if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: login.php");
    }
    
}

function upload()
{
    if (isset($_POST['upload'])) {
        header("Location: index.php");
    }
}
