<?php
?>
<html>
<p>Вы не авторизованы.</p>
<p>Введите пароль и нажмите кнопку "Войти"</p>

<form name="xmlForm" action="<?php $_SERVER['DOCUMENT_ROOT']?>/includes/auth.php" method="POST" enctype="multipart/form-data">
    <input type="password" name="signInPass" value="<?php if(isset($password)){echo $password;}?>" placeholder="Ваш пароль">
    
    <input type="submit" value="Войти" name="signInButton" />
   
</form>
</html>