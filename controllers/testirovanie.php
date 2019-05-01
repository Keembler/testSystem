<?
/**
* полечение вопрос/ответы
**/
function get_test_data($test_id,$link){
	if(!$test_id) return false;
	$query = mysqli_query($link, "SELECT q.question, q.parent_test, q.type_answer, a.id, a.answer, a.parent_question FROM questions q LEFT JOIN answers a ON q.id = a.parent_question LEFT JOIN tests ON tests.id = q.parent_test WHERE q.parent_test = $test_id AND tests.enable = '1'");
	$data = null;
	while ($row = mysqli_fetch_assoc($query)) {
		$data[$row['parent_question']][0] = $row['question'];
		$data[$row['parent_question']][$row['id']] = $row['answer'];
		$data[$row['parent_question']]['type_answer'] = $row['type_answer'];
	}
	return $data;
}

/**
* получение правильных ответов к вопросам
**/
function get_correct_answers($test,$link){
	if( !$test ) return false;
	$query = mysqli_query($link, "SELECT q.id AS question_id, a.answer AS answer_id FROM questions q LEFT JOIN answers a ON q.id = a.parent_question LEFT JOIN tests ON tests.id = q.parent_test WHERE q.parent_test = $test AND a.correct_answer = '1' AND tests.enable = '1'");
	$data = null;
	while($row = mysqli_fetch_assoc($query)){
		$data[$row['question_id']] = $row['answer_id'];
	}
	var_dump($data);
	return $data;
}

/**
* итоги
* 1 - массив вопрос/ответы
* 2 - правильные ответы
* 3 - ответы пользователя
**/
function get_test_data_result($test_all_data,$result,$resuser){
	// заполняем массив $test_all_data правильными ответами и данными о неотвеченных вопросах
	foreach ($result as $q => $a) {
		$test_all_data[$q]['correct_answer'] = $a;

		// добавим в массив данные о неотвеченных вопросах
		if( !isset($resuser[$q]) ){
			$test_all_data[$q]['incorrect_answer'] = 0;
		}
	}

	// добавим неверный ответ, если таковой был
	foreach ($resuser as $q => $a) {
		if($q !== 'test'){
			if( $test_all_data[$q]['correct_answer'] != $a ){
				$test_all_data[$q]['incorrect_answer'] = $a;
			}
		}
	}
	return $test_all_data;
}

function print_result($test_all_data_result){
	$all_count = count($test_all_data_result); // кол-во вопросов
	$correct_answer_count = 0; // кол-во верных ответов
	$incorrect_answer_count = 0; // кол-во неверных ответов
	$percent = 0; // процент верных ответов

	// подсчет результатов
	foreach ($test_all_data_result as $item) {
		if( isset($item['incorrect_answer'])) $incorrect_answer_count++;
	}
	$correct_answer_count = $all_count - $incorrect_answer_count;
	$percent = round( ($correct_answer_count / $all_count * 100), 2);
	
	// вывод результатов
	$print_res = '<div class="questions">';
		$print_res .= '<div class="count-res">';
			$print_res .= "<div class='all-count'>Всего вопросов: <b>{$all_count}</b></div>";
			$print_res .= "<div class='correct-count'>Отвечено верно: <b>{$correct_answer_count}</b></div>";
			$print_res .= "<div class='incorrect-count'>Отвечено неверно: <b>{$incorrect_answer_count}</b></div>";
			$print_res .= "<div class='percent'>Процент верных ответов: <b>{$percent}</b></div>";
		$print_res .= '</div>';

	// вывод теста...
	foreach ($test_all_data_result as $id_question => $item) { // получаем вопрос + ответы
		$correct_answer = $item['correct_answer'];
		$incorrect_answer = null;
		if( isset($item['incorrect_answer']) ){
			$incorrect_answer = $item['incorrect_answer'];
			$class = 'question-res error';
		}else{
			$class = 'question-res ok';
		}
		$print_res .= "<div class='$class'>";
		foreach ($item as $id_answer => $answer) { // проходим по массиву ответов
			if( $id_answer === 0 ){
				// вопрос
				$print_res .= "<p class='q'><b>$answer</b></p><hr>";
			}elseif( is_numeric($id_answer) ){
				// ответ
				if( $id_answer == $correct_answer ){
					// если это верный ответ
					$class = 'a ok2';
				}elseif( $id_answer == $incorrect_answer ){
					// если это неверный ответ
					$class = 'a error2';
				}else{
					$class = 'a';
				}
				$print_res .= "<p class='$class'>$answer</p>";
			}
		}
		$print_res .= "</div>"; // class='$class'
	}
	$print_res .= '<a href="/testirovanie" id="btn" class="btn red">Закончить</a>';
	$print_res .= '</div>'; // class="questions"

	return $print_res;
}

if (isset($_POST['test'])) {
	$test = (int)$_POST['test'];
	$resuser = $_POST;
	$result = get_correct_answers($test,$link);
	if ( !is_array($result) ) exit('Ошибка, массив не заполнен!');
	// данные теста
	$test_all_data = get_test_data($test,$link);
	// 1 - массив вопрос/ответы, 2 - правильные ответы, 3 - ответы пользователя
	$test_all_data_result = get_test_data_result($test_all_data,$result,$resuser);
	echo print_result($test_all_data_result);
	die;
}