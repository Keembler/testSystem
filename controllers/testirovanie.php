<?
/**
* полечение вопрос/ответы
**/
function get_test_data($test_id,$link){
	if(!$test_id) return false;
	$query = mysqli_query($link, "SELECT q.question, q.parent_test, q.type_answer, q.image, a.id, a.answer, a.parent_question FROM questions q LEFT JOIN answers a ON q.id = a.parent_question LEFT JOIN tests ON tests.id = q.parent_test WHERE q.parent_test = $test_id AND tests.enable = '1'");
	$data = null;
	while ($row = mysqli_fetch_assoc($query)) {
		$data[$row['parent_question']][0] = $row['question'];
		$data[$row['parent_question']][$row['id']] = $row['answer'];
		$data[$row['parent_question']]['type_answer'] = $row['type_answer'];
		$data[$row['parent_question']]['image'] = $row['image'];
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
		if ($data !== null and array_key_exists($row['question_id'], $data)) {
			$data[$row['question_id']] = $data[$row['question_id']] .','.$row['answer_id'];
		}
		else {
			$data[$row['question_id']] = $row['answer_id'];
		}
		
	}
	// var_dump($data);
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

function print_result($test_all_data_result,$link,$test,$id_user){
	$query_test_correct = mysqli_fetch_array(mysqli_query($link, "SELECT correct FROM tests WHERE id = $test"));

	$all_count = count($test_all_data_result); // кол-во вопросов
	$correct_answer_count = 0; // кол-во верных ответов
	$incorrect_answer_count = 0; // кол-во неверных ответов
	$percent = 0; // процент верных ответов
	$counter = 0;

	$print_res = '<div class="questions">';
	if($query_test_correct['correct'] == 1){
	// вывод теста...
	foreach ($test_all_data_result as $id_question => $item) { // получаем вопрос + ответы
		$correct_answer = explode(",",$item['correct_answer']);
		$counter_correct_user_answers = 0;
		$several_correct_answer = count($correct_answer);
		
		
		$incorrect_answer = null;
		if( isset($item['incorrect_answer']) ){
			$incorrect_answer = explode(",",$item['incorrect_answer']);
			$class = 'question-res error';
		}else{
			$class = 'question-res ok';
		}

		$print_res .= "<div class='$class'>";
		
		foreach ($item as $id_answer => $answer) {
			// проходим по массиву ответов
			if( $id_answer === 0 ){
				// вопрос
				$print_res .= "<p class='q'><b>$answer</b></p><hr>";
			}elseif( is_numeric($id_answer) ){
				$class = 'a';
				foreach ($correct_answer as $key => $value) {
					// ответ
					if( $answer == $value ){
						// если это верный ответ
						$class = 'a ok2';
						break;
					}
				}
				if (isset($incorrect_answer)) {
		          if (is_array($incorrect_answer)) {
		            foreach ($incorrect_answer as $key => $value) {
		              // ответ
		              // print_r($correct_answer);
		              if( $answer == $value and in_array($value, $correct_answer)){
		                // если это верный ответ
		                $class = 'a ok3';
		                $counter_correct_user_answers++;
		                break;
		              }elseif ($answer == $value and !in_array($value, $correct_answer)){
		                // если это неверный ответ
		                $class = 'a error2';
		                $counter_correct_user_answers = 0;
		              }
		            }
		          }
		          else {
		            if( $incorrect_answer == $answer){
		              // если это верный ответ
		              $class = 'a ok2';
		              break;
		            }else{
		              // если это неверный ответ
		              $class = 'a error2';
		            }
		          }
		        }
		        if(count($correct_answer) > 1){
			        if(isset($incorrect_answer)){
		                $counter_correct_user_answers;
			        }else{
	                	$counter_correct_user_answers = 0;
			        }
			    }
				$print_res .= "<p class='$class'>$answer</p>";
			}
		}
		if(count($correct_answer) > 1){
			$counter += $counter_correct_user_answers / $several_correct_answer;
		}
		$print_res .= "</div>"; // class='$class'
	}
	}

	// подсчет результатов
	foreach ($test_all_data_result as $item) {
		if( isset($item['incorrect_answer'])){
			$incorrect_answer_count++;
		}
	}
	$incorrect_answer_count = $incorrect_answer_count - $counter;
	$correct_answer_count = $all_count - $incorrect_answer_count;
	$percent = round( ($correct_answer_count / $all_count * 100), 2);
	$ocenka = 2;
	if ($percent >= 50 && $percent < 73) {
		$ocenka = 3;
	}elseif($percent >= 73 && $percent < 85){
		$ocenka = 4;
	}elseif($percent >= 86 && $percent <= 100){
		$ocenka = 5;
	}
	
	// вывод результатов
		$print_res .= '<div class="count-res">';
			$print_res .= "<div class='all-count'>Всего вопросов: <b>{$all_count}</b></div>";
			$print_res .= "<div class='correct-count'>Отвечено верно: <b>{$correct_answer_count}</b></div>";
			$print_res .= "<div class='incorrect-count'>Отвечено неверно: <b>{$incorrect_answer_count}</b></div>";
			$print_res .= "<div class='info'><h4>Оценка за тест выставляется в соответствии с количеством правильных ответов. Если Вы набрали:</h4>";
			$print_res .= "<p>- меннее 50% - тестирование не пройдено (неуд)</p>";
			$print_res .= "<p>- от 50% до 72% - оценка 3</p>";
			$print_res .= "<p>- от 73% до 85% - оценка 4</p>";
			$print_res .= "<p>- от 86% до 100% - оценка 5</p>";
			$print_res .= "<h4>Ваша оценка по данному тесту <b>{$ocenka}</b>(<b>{$percent}</b>)</h4>";
			$print_res .= "</div>"; // class="info"
		$print_res .= '</div>';
	$print_res .= '<a href="/testirovanie" id="btn" class="btn red" style="margin-top: 15px;">Закончить</a>';
	$print_res .= '</div>'; // class="questions"

	// Запись результата
	$insert_result = mysqli_query($link, "INSERT INTO `results_test` (`id_test`, `id_user`, `result`, `ocenka`) VALUES('$test', '$id_user', '$percent', ' $ocenka')");

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
	// print_r($test_all_data_result);
	echo print_result($test_all_data_result,$link,$test,$id_user);
	die;
}

if(isset($_GET['time'])){
	$query = mysqli_query($link, "SELECT * FROM tests WHERE id = $_GET[test_id] AND enable = '1'");
	$row = mysqli_fetch_array($query);
	echo $row['time'];
}
