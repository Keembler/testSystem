<?
if ($page === 'add_test' and $_SESSION['$root'] == 1 and isset($_POST['name'])) {
	$name = $_POST['name'];
	if (isset($_POST['enable'])) {
		$active = 1;
	}
	else {
		$active = 0;
	}

	$query = mysqli_query($link, "INSERT INTO `tests` (`name`, `enable`) VALUES('$name','$active')");

	if ($query) {
		$resp = '{"status": 200, "text":"Новый тест добавлен"}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}
else if ($page === 'rm_test' and $_SESSION['$root'] == 1 and isset($_POST['id']) and $_POST['id'] !== '') {
	$testID = $_POST['id'];
	$query = mysqli_query($link, "DELETE FROM `tests` WHERE `id` = $testID");

	if ($query) {
		$resp = '{"status": 200, "text":"Тест c ID = '.$testID.' удалён"}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}
