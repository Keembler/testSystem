<?
if ($page === 'add_question' and $_SESSION['$root'] == 1 and isset($_POST['question'])) {
	$name = $_POST['question'];
	$test = $_POST['parent_test'];

	$query = mysqli_query($link, "INSERT INTO `questions` (`question`, `parent_test`) VALUES('$name','$test')");

	if ($query) {
		$resp = '{"status": 200, "text":"Новый вопрос добавлен"}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}
else if ($page === 'rm_question' and $_SESSION['$root'] == 1 and isset($_POST['id']) and $_POST['id'] !== '') {
	$questionID = $_POST['id'];
	$query = mysqli_query($link, "DELETE FROM `questions` WHERE `id` = $questionID");

	if ($query) {
		$resp = '{"status": 200, "text":"Вопрос c ID = '.$questionID.' удалён"}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}
else if ($page === 'ed_question' and $_SESSION['$root'] == 1 and isset($_POST['id']) and $_POST['id'] !== '') {
	$questionID = $_POST['id'];

	$query = mysqli_query($link, "SELECT * FROM `questions` WHERE `id` = $questionID");
	$question = json_encode(mysqli_fetch_array($query));
	if ($query) {
		$resp = '{"status": 200, "text":"Вопрос c ID = '.$questionID.' получен", "question":'.$question.'}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}
else if ($page === 'save_question' and $_SESSION['$root'] == 1) {
	
	$questionID = $_POST['id_question'];
	$name = $_POST['question'];
	$test = $_POST['parent_test'];

	if (isset($_POST['question']))  {
		$query = mysqli_query($link, "UPDATE `questions` SET `question` = '$name', `parent_test` = '$test' WHERE `id` = $questionID");
	}

	if ($query) {
		$resp = '{"status": 200, "text":"Новая информация сохранена успешно!"}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}