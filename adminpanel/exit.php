<?php
session_start();//стартуем сессию
unset ($_SESSION['$logSESS']);//удаляем зарегистрированную глобальную переменную
unset ($_SESSION['$role']);//удаляем зарегистрированную глобальную переменную
session_destroy();//уничтожаем сессию
header("location: ../auth/login.php");//перебрасываем на главную страницу пользовательской части блога
exit;
?>