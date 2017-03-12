<?php

session_start();
if (isset($_POST['signInPass']) && trim(strip_tags($_POST['signInPass'])) != '') {
    $password = trim(strip_tags($_POST['signInPass']));
    if (md5($password) === '590f5f490e99ee1e305e03bdefcba522') {
        //СлушайтесьИнночку
        echo 'Проект закрыт!!!';
        include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/authForm.php';
    } elseif (md5($password) === '8c2a254becb93d87d2d64cf7ce09d16e') {
        $_SESSION['signedIn'] = 'loggedIn';
        header('Location: /');
    } elseif (md5($password) === '02f9626d1f5ab824b7cfdf234fd6e37e') {
        $_SESSION['signedIn'] = 'loggedIn';
        header('Location: /');
    } else {
        echo '<p>Неправильный пароль.<br>Вы вводили: "' . $password . '</p>';
        include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/authForm.php';
    }
} else {
    echo 'Введите пароль';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/authForm.php';
}

