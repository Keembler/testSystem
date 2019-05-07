<?
if ($page === 'add_question_poll' and $_SESSION['$root'] == 1 and isset($_POST['question']) and isset($_POST['answers'])) {

	$name = $_POST['question'];
	$pollID = $_POST['parent_poll'];
	$answers = json_decode($_POST['answers']);

	/**
	 * Записываем новый вопрос в базу данных
	 */
	$query = mysqli_query($link, "INSERT INTO `questions_polls` (`question`, `parent_poll`) VALUES('$name','$pollID')");

	/**
	 * Получаем ID последнего вопроса из базы
	 */
	$query_last_question = mysqli_query($link, "SELECT * FROM `questions_polls` ORDER BY id DESC LIMIT 1");

	$question = mysqli_fetch_array($query_last_question);

	/**
	 * Записыаем ответы в базу данных используя полученный ранее ID вопроса
	 *
	 * Для начала нужно сформировать список вопросов для записи
	 */
	
	$VALUES = "";
	if (count($answers) > 1){
		for ($i=0; $i < count($answers); $i++) { 
			if ($i < count($answers)-1) {
				$VALUES .= "('".$answers[$i]->answer."','".$question['id']."'),";
			}
			else {
				$VALUES .= "('".$answers[$i]->answer."','".$question['id']."')";
			}
		}
	} else {
		$VALUES .= "('".$answers[0]->answer."','".$question['id']."')";
	}

	$query_answers = mysqli_query($link, "INSERT INTO `answers_polls` (`answer`, `parent_question`) VALUES $VALUES");

	$question = json_encode($question);
	
	if ($query and $query_answers) {
		$resp = '{"status": 200, "text":"Новый вопрос c ответами добавлен", "question":'.$question.'}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}
else if ($page === 'rm_question_poll' and $_SESSION['$root'] == 1 and isset($_POST['id']) and $_POST['id'] !== '') {
	$questionID = $_POST['id'];
	$query = mysqli_query($link, "DELETE FROM `questions_polls` WHERE `id` = $questionID");

	if ($query) {
		$resp = '{"status": 200, "text":"Вопрос c ID = '.$questionID.' удалён"}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}
else if ($page === 'ed_question_poll' and $_SESSION['$root'] == 1 and isset($_POST['id']) and $_POST['id'] !== '') {
	$questionID = $_POST['id'];
	$answers = [];
	$query = mysqli_query($link, "SELECT * FROM `questions_polls` WHERE `id` = $questionID");
	$query2 = mysqli_query($link, "SELECT * FROM `answers_polls` WHERE `parent_question` = $questionID");
	$question = json_encode(mysqli_fetch_array($query));
	
	while($answer = mysqli_fetch_array($query2)){
		$answers[] = json_decode('{ "id": '.$answer["id"].', "answer": "'.$answer["answer"].'", "parent_question": '.$answer["parent_question"].'}');
	}
	$json_answers = json_encode($answers);

	if ($query and $query2) {
		$resp = '{"status": 200, "text":"Вопрос c ID = '.$questionID.' получен", "question":'.$question.', "answers": '.$json_answers.'}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}
else if ($page === 'save_question_poll' and $_SESSION['$root'] == 1) {
	
	$questionID = $_POST['id_question'];
	$name = $_POST['question'];
	$poll = $_POST['parent_poll'];
	$answers = json_decode($_POST['answers']);

	if (isset($_POST['question']))  {
		$query = mysqli_query($link, "UPDATE `questions_polls` SET `question` = '$name', `parent_poll` = '$poll' WHERE `id` = $questionID");
	}

	if (isset($_POST['answers']))  {
		$ok = false;
		for ($i=0; $i < count($answers); $i++) { 
			$query2 = mysqli_query($link, "UPDATE `answers_polls` SET `answer` = '".$answers[$i]->answer."', `parent_question` = '".$questionID."' WHERE `id` = ".$answers[$i]->id);
			if ($query2) {
				$ok = true;
			}
		}
	}

	if ($query or $ok) {
		$resp = '{"status": 200, "text":"Новая информация сохранена успешно!"}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}