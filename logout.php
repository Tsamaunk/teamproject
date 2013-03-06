<?php

session_start();
unset($_SESSION['userId']);
unset($_SESSION['userToken']);
session_destroy();
header('Location: index.php');
?>