<?
if ($page === 'add_user' and $_SESSION['$root'] == 1 and isset($_POST['login']) and isset($_POST['pass'])) {
	$fio = $_POST['fio'];
	$login = $_POST['login'];
	$pass = md5($_POST['pass']);
	$newRole = $_POST['role'];
	if (isset($_POST['root'])) {
		$newRoot = 1;
	}
	else {
		$newRoot = 0;
	}

	$query = mysqli_query($link, "INSERT INTO `users` (`fio`, `login`, `password`, `role`, `root`) VALUES('$fio','$login','$pass','$newRole','$newRoot')");

	if ($query) {
		$resp = '{"status": 200, "text":"Новый пользователь добавлен"}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}
else if ($page === 'rm_user' and $_SESSION['$root'] == 1 and isset($_POST['id']) and $_POST['id'] !== '') {
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
else if ($page === 'ed_user' and $_SESSION['$root'] == 1 and isset($_POST['id']) and $_POST['id'] !== '') {
	$userID = $_POST['id'];
	$fio = $_POST['fio'];
	$login = $_POST['login'];
	$pass = md5($_POST['pass']);
	$newRole = $_POST['role'];
	if (isset($_POST['root'])) {
		$newRoot = 1;
	}
	else {
		$newRoot = 0;
	}
	$query = mysqli_query($link, "UPDATE `users` SET fio='$fio', login='$login', password='$pass', role='$newRole', root='$newRoot'");

	if ($query) {
		$resp = '{"status": 200, "text":"Пользователь c ID = '.$userID.' изменён"}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}
