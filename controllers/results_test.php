<?
if ($page === 'rm_result' and $_SESSION['$root'] == 1 and isset($_POST['id']) and $_POST['id'] !== '') {
	$resultID = $_POST['id'];
	$query = mysqli_query($link, "DELETE FROM `results_test` WHERE `id` = $resultID");

	if ($query) {
		$resp = '{"status": 200, "text":"Результат c ID = '.$resultID.' удалён"}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}
else if ($page === 'view_result' and $_SESSION['$root'] == 1 and isset($_POST['id']) and $_POST['id'] !== '') {
	$resultID = $_POST['id'];
	$query = mysqli_query($link, "SELECT * FROM `view_quest_result` WHERE `id` = $resultID");
	$result = json_encode(mysqli_fetch_array($query));

	if ($query) {
		$resp = '{"status": 200, "text":"Результат c ID = '.$resultID.' получен"}';
	}
	else {
		$resp = '{"status": 400, "text":"Ошибочка"}';
	}
	exit($resp);
}