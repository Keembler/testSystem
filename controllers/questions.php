<?
if ($page === 'add_question' and $_SESSION['$root'] == 1 and isset($_POST['question']) and isset($_POST['answers'])) {

	$question = $_POST['question'];
	$testID = $_POST['parent_test'];
	$answers = json_decode($_POST['answers']);


	/**
	 * Записываем новый вопрос в базу данных
	 */
	$query = mysqli_query($link, "INSERT INTO `questions` (`question`, `parent_test`) VALUES('$question','$testID')");

	/**
	 * Получаем ID последнего вопроса из базы
	 */
	$query_last_question = mysqli_query($link, "SELECT id FROM questions ORDER BY id DESC LIMIT 1");

	$questionID = mysqli_fetch_array($query_last_question);


	/**
	 * Записыаем ответы в базу данных используя полученный ранее ID вопроса
	 *
	 * Для начала нужно сформировать список вопросов для записи
	 */
	
	$VALUES = "";
	for ($i=0; $i < count($answers); $i++) { 
		if ($i < count($answers)-1) {
			$VALUES .= "('".$answers[$i]->answer."',".$questionID['id'].",'".$answers[$i]->correct_answer."'),";
		}
		else {
			$VALUES .= "('".$answers[$i]->answer."',".$questionID['id'].",'".$answers[$i]->correct_answer."')";
		}
	}
	
	$query_answers = mysqli_query($link, "INSERT INTO `answers` (`answer`, `parent_question`, `correct_answer`) VALUES $VALUES");

	
	if ($query and $query_answers) {
		$resp = '{"status": 200, "text":"Новый вопрос c ответами добавлен"}';
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