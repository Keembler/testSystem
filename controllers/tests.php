<?
if ($page === 'add_test' and $_SESSION['$root'] == 1 and isset($_POST['name'])) {
	$name = $_POST['name'];
	$time = $_POST['time'];
	if (isset($_POST['enable'])) {
		$active = 1;
	}
	else {
		$active = 0;
	}
	if (isset($_POST['correct'])) {
		$correct = 1;
	}
	else {
		$correct = 0;
	}
	if($time <= 60 && $time >= 1){
		$query = mysqli_query($link, "INSERT INTO `tests` (`name`, `time`, `enable`, `correct`) VALUES('$name', '$time', $active', '$correct')");
	}
	$query2 = mysqli_query($link, "SELECT * FROM `tests` ORDER BY id DESC LIMIT 1");
	$test = json_encode(mysqli_fetch_array($query));

	if (isset($query) && $query2) {
		$resp = '{"status": 200, "text":"Новый тест добавлен", "test":'.$test.'}';
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
else if ($page === 'ed_test' and $_SESSION['$root'] == 1 and isset($_POST['id']) and $_POST['id'] !== '') {
	$testID = $_POST['id'];

	$query = mysqli_query($link, "SELECT * FROM `tests` WHERE `id` = $testID");
	$test = json_encode(mysqli_fetch_array($query));
	if ($query) {
		$resp = '{"status": 200, "text":"Тест c ID = '.$testID.' получен", "test":'.$test.'}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}
else if ($page === 'save_test' and $_SESSION['$root'] == 1) {
	
	$testID = $_POST['id_test'];
	$name = $_POST['name'];
	$time = $_POST['time'];
	if (isset($_POST['enable'])) {
		$active = 1;
	}
	else {
		$active = 0;
	}
	if (isset($_POST['correct'])) {
		$correct = 1;
	}
	else {
		$correct = 0;
	}

	if (isset($_POST['name']))  {
		if($time <= 60 && $time >= 1){
			$query = mysqli_query($link, "UPDATE `tests` SET `name` = '$name', `time` = '$time', `enable` = '$active', `correct` = '$correct' WHERE `id` = $testID");
		}
	}

	if (isset($query)) {
		$resp = '{"status": 200, "text":"Новая информация сохранена успешно!"}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}