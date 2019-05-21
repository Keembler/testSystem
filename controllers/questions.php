<?
if ($page === 'add_question' and $_SESSION['$root'] == 1 and isset($_POST['question']) and isset($_POST['answers'])) {

	$name = $_POST['question'];
	$testID = $_POST['parent_test'];
	$answers = json_decode($_POST['answers']);
	$type_answer = $_POST['type_answer']; 
	$upload_image='';
	$folder='';
	if($_FILES){
		$pattern = "[^a-zа-яё0-9,~!@#%^-_\$\?\(\)\{\}\[\]\.]";
		$upload_image=$_FILES["files"]["name"];
        $upload_image = mb_eregi_replace($pattern, '-', $upload_image);
        $upload_image = mb_ereg_replace('[-]+', '-', $upload_image);
        
        // Т.к. есть проблема с кириллицей в названиях файлов (файлы становятся недоступны).
        // Сделаем их транслит:
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',    'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',    'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',    'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',    'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',  'ь' => '',    'ы' => 'y',   'ъ' => '',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya', 
        
            'А' => 'A',   'Б' => 'B',   'В' => 'V',    'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',    'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',    'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',    'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',  'Ь' => '',    'Ы' => 'Y',   'Ъ' => '',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );

        $upload_image = strtr($upload_image, $converter);
		
		$folder="./images/";
		move_uploaded_file($_FILES["files"]["tmp_name"],"$folder".$upload_image);
	}

	/**
	 * Записываем новый вопрос в базу данных
	 */
	$query = mysqli_query($link, "INSERT INTO `questions` (`question`, `parent_test`, `type_answer`, `image`) VALUES('$name','$testID', '$type_answer', '$folder$upload_image')");

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
		$VALUES .= "('".$answers[0]->answer."',".$question['id'].",'".$answers[0]->correct_answer."')";
	}

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
	$answers = [];
	$query = mysqli_query($link, "SELECT * FROM `questions` WHERE `id` = $questionID");
	$query2 = mysqli_query($link, "SELECT * FROM `answers` WHERE `parent_question` = $questionID");
	$question = json_encode(mysqli_fetch_array($query));
	
	while($answer = mysqli_fetch_array($query2)){
		$answers[] = json_decode('{ "id": '.$answer["id"].', "answer": "'.$answer["answer"].'", "parent_question": '.$answer["parent_question"].', "correct_answer": '.$answer["correct_answer"].'}');
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
else if ($page === 'save_question' and $_SESSION['$root'] == 1) {
	
	$questionID = $_POST['id_question'];
	$name = $_POST['question'];
	$type_answer = $_POST['type_answer'];
	$test = $_POST['parent_test'];
	$answers = json_decode($_POST['answers']);
	$upload_image='';
	$folder='';
	if($_FILES){
		$pattern = "[^a-zа-яё0-9,~!@#%^-_\$\?\(\)\{\}\[\]\.]";
		$upload_image=$_FILES["files"]["name"];
        $upload_image = mb_eregi_replace($pattern, '-', $upload_image);
        $upload_image = mb_ereg_replace('[-]+', '-', $upload_image);
        
        // Т.к. есть проблема с кириллицей в названиях файлов (файлы становятся недоступны).
        // Сделаем их транслит:
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',    'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',    'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',    'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',    'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',  'ь' => '',    'ы' => 'y',   'ъ' => '',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya', 
        
            'А' => 'A',   'Б' => 'B',   'В' => 'V',    'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',    'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',    'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',    'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',  'Ь' => '',    'Ы' => 'Y',   'Ъ' => '',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );

        $upload_image = strtr($upload_image, $converter);
		
		$folder="./images/";
		move_uploaded_file($_FILES["files"]["tmp_name"],"$folder".$upload_image);
	}

	if (isset($_POST['question']))  {
		$query = mysqli_query($link, "UPDATE `questions` SET `question` = '$name', `parent_test` = '$test', `type_answer` = '$type_answer', `image` = '$folder$upload_image' WHERE `id` = $questionID");
	}

	if (isset($_POST['answers']))  {
		$ok = false;
		for ($i=0; $i < count($answers); $i++) { 
			$query2 = mysqli_query($link, "UPDATE `answers` SET `answer` = '".$answers[$i]->answer."', `parent_question` = ".$questionID.", `correct_answer` = ".$answers[$i]->correct_answer." WHERE `id` = ".$answers[$i]->id);
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