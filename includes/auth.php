<?php

session_start();
if (isset($_POST['signInPass']) && trim(strip_tags($_POST['signInPass'])) != '') {
    $password = trim(strip_tags($_POST['signInPass']));
    if ($password === 'CkeifqntcmByyjxre') {
        //СлушайтесьИнночку
        $_SESSION['signedIn'] = 'loggedIn';
        header('Location: /');
    } else {
        echo '<p>Неправильный пароль.<br>Вы вводили: "'.$password.'</p>';
        include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/authForm.php';
    }
} else {
    echo 'Введите пароль';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/authForm.php';
}

