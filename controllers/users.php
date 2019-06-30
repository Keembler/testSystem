<?
if ($page === 'add_user' and $_SESSION['$role'] == 'Администратор' and isset($_POST['login']) and isset($_POST['pass'])) {
	$fio = $_POST['fio'];
	$login = $_POST['login'];
	$pass = md5($_POST['pass']);
	$newRole = $_POST['role'];

	$query = mysqli_query($link, "INSERT INTO `users` (`fio`, `login`, `password`, `role`) VALUES('$fio','$login','$pass','$newRole')");

	$query = mysqli_query($link, "SELECT * FROM `users` ORDER BY id DESC LIMIT 1");
	$user = json_encode(mysqli_fetch_array($query));

	if ($query) {
		$resp = '{"status": 200, "text":"Новый пользователь добавлен", "user":'.$user.'}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}
else if ($page === 'rm_user' and $_SESSION['$role'] == 'Администратор' and isset($_POST['id']) and $_POST['id'] !== '') {
	$userID = $_POST['id'];
	$query = mysqli_query($link, "DELETE FROM `users` WHERE `id` = $userID");

	if ($query) {
		$resp = '{"status": 200, "text":"Пользователь c ID = '.$userID.' удалён"}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}
else if ($page === 'ed_user' and $_SESSION['$role'] == 'Администратор' and isset($_POST['id']) and $_POST['id'] !== '') {
	$userID = $_POST['id'];

	$query = mysqli_query($link, "SELECT * FROM `users` WHERE `id` = $userID");
	$user = json_encode(mysqli_fetch_array($query));
	if ($query) {
		$resp = '{"status": 200, "text":"Пользователь c ID = '.$userID.' получен", "user":'.$user.'}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}
else if ($page === 'save_user' and $_SESSION['$role'] == 'Администратор') {
	
	$userID = $_POST['id_user'];
	$fio = $_POST['fio'];
	$login = $_POST['login'];
	if (isset($_POST['pass']) and $_POST['pass'] !== '')  {
		$pass = md5($_POST['pass']);
	}
	$newRole = $_POST['role'];

	if (isset($_POST['pass']) and $_POST['pass'] !== '')  {
		$query = mysqli_query($link, "UPDATE `users` SET `fio` = '$fio', `login` = '$login', `password` = '$pass', `role` = '$newRole' WHERE `id` = $userID");
	}
	else {
		$query = mysqli_query($link, "UPDATE `users` SET `fio` = '$fio', `login` = '$login', `role` = '$newRole' WHERE `id` = $userID");
	}

	if ($query) {
		$resp = '{"status": 200, "text":"Новая информация сохранена успешно!"}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}
