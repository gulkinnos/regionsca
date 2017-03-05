<html>
    <div id="signIn">
        <form name="xmlForm" action="<?php $_SERVER['DOCUMENT_ROOT'] ?>/includes/auth.php" method="POST" enctype="multipart/form-data">
            <span>Вы не авторизованы.<br>
                Введите пароль и нажмите кнопку "Войти"</span>
            <input type="password" name="signInPass" value="<?php
            if (isset($password)) {
                echo $password;
            }
            ?>" placeholder="Ваш пароль">
            <input type="submit" value="Войти" name="signInButton" />
        </form>
    </div>
</html>