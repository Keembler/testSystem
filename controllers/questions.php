<?
if ($page === 'add_question' and $_SESSION['$root'] == 1 and isset($_POST['question']) and isset($_POST['answers'])) {

	$name = $_POST['question'];
	$testID = $_POST['parent_test'];
	$answers = json_decode($_POST['answers']);
	$type_answer = $_POST['type_answer']; 


	/**
	 * Записываем новый вопрос в базу данных
	 */
	$query = mysqli_query($link, "INSERT INTO `questions` (`question`, `parent_test`, `type_answer`) VALUES('$name','$testID', '$type_answer')");

	/**
	 * Получаем ID последнего вопроса из базы
	 */
	$query_last_question = mysqli_query($link, "SELECT * FROM `questions` ORDER BY id DESC LIMIT 1");

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
				$VALUES .= "('".$answers[$i]->answer."',".$question['id'].",'".$answers[$i]->correct_answer."'),";
			}
			else {
				$VALUES .= "('".$answers[$i]->answer."',".$question['id'].",'".$answers[$i]->correct_answer."')";
			}
		}
	} else {
		$VALUES .= "('".$answers[0]->answer."',".$question['id'].",'".$answers[0]->correct_answer."'),";
	}

	/* НЕ РАБОТАЕТ ДОБАВЛЕНИЕ ОТВЕТА СЛОВОМ */

	$query_answers = mysqli_query($link, "INSERT INTO `answers` (`answer`, `parent_question`, `correct_answer`) VALUES $VALUES");

	$question = json_encode($question);
	
	if ($query and $query_answers) {
		$resp = '{"status": 200, "text":"Новый вопрос c ответами добавлен", "question":'.$question.'}';
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


	/* НЕ ДОДЕЛАНО РЕДАКТИРОВАНИЕ */


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