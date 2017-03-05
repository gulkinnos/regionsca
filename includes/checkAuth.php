<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="resources/css/main.css" >
</head>

<?php
session_start();
if (!isset($_SESSION['signedIn']) || $_SESSION['signedIn'] !== 'loggedIn') {
    include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/authForm.php';
    die();
} else {
    include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/outForm.php';
}