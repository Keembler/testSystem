<?
if ($page === 'add_poll' and $_SESSION['$root'] == 1 and isset($_POST['name'])) {
	$name = $_POST['name'];
	if (isset($_POST['enable'])) {
		$active = 1;
	}
	else {
		$active = 0;
	}

	$query = mysqli_query($link, "INSERT INTO `polls` (`name`, `enable`) VALUES('$name','$active')");

	$query = mysqli_query($link, "SELECT * FROM `polls` ORDER BY id DESC LIMIT 1");

	$poll = json_encode(mysqli_fetch_array($query));

	if ($query) {
		$resp = '{"status": 200, "text":"Новый опрос добавлен", "poll":'.$poll.'}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}
else if ($page === 'rm_poll' and $_SESSION['$root'] == 1 and isset($_POST['id']) and $_POST['id'] !== '') {
	$pollID = $_POST['id'];
	$query = mysqli_query($link, "DELETE FROM `polls` WHERE `id` = $pollID");

	if ($query) {
		$resp = '{"status": 200, "text":"Опрос c ID = '.$pollID.' удалён"}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}
else if ($page === 'ed_poll' and $_SESSION['$root'] == 1 and isset($_POST['id']) and $_POST['id'] !== '') {
	$pollID = $_POST['id'];

	$query = mysqli_query($link, "SELECT * FROM `polls` WHERE `id` = $pollID");
	$poll = json_encode(mysqli_fetch_array($query));
	if ($query) {
		$resp = '{"status": 200, "text":"Опрос c ID = '.$pollID.' получен", "poll":'.$poll.'}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}
else if ($page === 'save_poll' and $_SESSION['$root'] == 1) {
	
	$pollID = $_POST['id_poll'];
	$name = $_POST['name'];
	if (isset($_POST['enable'])) {
		$active = 1;
	}
	else {
		$active = 0;
	}

	if (isset($_POST['name']))  {
		$query = mysqli_query($link, "UPDATE `polls` SET `name` = '$name', `enable` = '$active' WHERE `id` = $pollID");
	}

	if ($query) {
		$resp = '{"status": 200, "text":"Новая информация сохранена успешно!"}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}