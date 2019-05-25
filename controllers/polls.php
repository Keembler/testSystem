<?
if ($page === 'add_poll' and ($_SESSION['$role'] == 'Администратор' || $_SESSION['$role'] == 'Преподаватель') and isset($_POST['name'])) {
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
else if ($page === 'rm_poll' and ($_SESSION['$role'] == 'Администратор' || $_SESSION['$role'] == 'Преподаватель') and isset($_POST['id']) and $_POST['id'] !== '') {
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
else if ($page === 'ed_poll' and ($_SESSION['$role'] == 'Администратор' || $_SESSION['$role'] == 'Преподаватель') and isset($_POST['id']) and $_POST['id'] !== '') {
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
else if ($page === 'result_poll' and ($_SESSION['$role'] == 'Администратор' || $_SESSION['$role'] == 'Преподаватель') and isset($_POST['id']) and $_POST['id'] !== '') {
	$pollID = $_POST['id'];

		
	$query2 = mysqli_query($link, "SELECT * FROM `questions_polls` WHERE `parent_poll` = $pollID");
	$poll_str = [];
	$first = 0;
	while($res_poll = mysqli_fetch_array($query2)) {

		$query = mysqli_query($link, "SELECT `ap`.`answer`, `ap`.`votes` FROM `answers_polls` as `ap` WHERE `ap`.`parent_question` = $res_poll[id]");

		$sum = mysqli_fetch_array(mysqli_query($link, "SELECT max(`ap`.`votes`) AS `max_v`, sum(`ap`.`votes`) AS `sum_v` FROM `answers_polls` as `ap` WHERE `ap`.`parent_question` = $res_poll[id] LIMIT 1"));


		$poll_arr = [];
		while($res_poll_in = mysqli_fetch_array($query)) {
			$poll_arr[] = json_decode('{"answer":"'.$res_poll_in["answer"].'", "votes":"'.$res_poll_in["votes"].'"}');
		}
		
		$poll_str[] = json_decode('{"question":"'.$res_poll["question"].'", "data":'.json_encode($poll_arr).', "max_v":"'.$sum["max_v"].'", "sum_v": "'.$sum["sum_v"].'"}');
		
	}
	
	

	if ($query) {
		$resp = '{"status": 200, "text":"Опрос c ID = '.$pollID.' получен", "poll":'.json_encode($poll_str).'}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}
else if ($page === 'save_poll' and ($_SESSION['$role'] == 'Администратор' || $_SESSION['$role'] == 'Преподаватель')) {
	
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