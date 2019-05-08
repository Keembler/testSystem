<?
/**
* полечение вопрос/ответы
**/
function get_poll_data($poll_id,$link){
	if(!$poll_id) return false;
	$query = mysqli_query($link, "SELECT q.question, q.parent_poll, a.id, a.answer, a.parent_question FROM questions_polls q LEFT JOIN answers_polls a ON q.id = a.parent_question LEFT JOIN polls ON polls.id = q.parent_poll WHERE q.parent_poll = $poll_id AND polls.enable = '1'");
	$data = null;
	while ($row = mysqli_fetch_assoc($query)) {
		$data[$row['parent_question']][0] = $row['question'];
		$data[$row['parent_question']][$row['id']] = $row['answer'];
	}
	return $data;
}

function get_poll_data_result($poll_all_data,$result){
	foreach ($result as $q => $a) {
		$poll_all_data[$q]['answer'] = $a;
	}

	return $poll_all_data;
}

function save_result($poll_all_data_result,$link){

	$print_res = '<h1>Спасибо, Ваш голос учтен!</h1>';
	$answer_id = '';

	foreach ($poll_all_data_result as $q => $a) {
		$answer_id = $poll_all_data_result[$q]['answer'];

		// Сохранение голосования
		$update_result = mysqli_query($link, "UPDATE answers_polls SET votes=(votes+1) WHERE id='".$answer_id."' LIMIT 1");

	}


	return $print_res;
}

if (isset($_POST['poll'])) {
	$poll = (int)$_POST['poll'];
	$result = $_POST;
	$id_poll = array_pop($result);
	// данные теста
	$poll_all_data = get_poll_data($poll,$link);
	$poll_all_data_result = get_poll_data_result($poll_all_data,$result);
	echo save_result($poll_all_data_result,$link);
	die;
}
