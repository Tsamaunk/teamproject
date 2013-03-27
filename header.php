<?php
include_once 'base.php'; // THIS THE THE BACKEND CONNECTOR
$hlp = new Helper();  // CREATE THE HELPER

if (isset($_SESSION['userId']) && isset($_SESSION['userToken']) && $hlp->validToken($_SESSION['userId'], $_SESSION['userToken'])) {
    $loggedin = 1;
    $myId = $_SESSION['userId'];
} else {
    $loggedin = 0;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
    <head>
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.validate.js"></script>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Department of Residential Life- University at Albany - SUNY </title>
        <link rel="stylesheet" href="css/html_all_2.css" type="text/css"  />	
        <link rel="stylesheet" href="css/html_all_2_print.css" media="print" type="text/css" />
        <link rel="stylesheet" href="css/assessment_icon.css" type="text/css"  />        
        <link rel="stylesheet" href="css/adminConsole.css" type="text/css"  />
    </head>