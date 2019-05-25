<?
if ($page === 'rm_result' and ($_SESSION['$role'] == 'Администратор' || $_SESSION['$role'] == 'Преподаватель') and isset($_POST['id']) and $_POST['id'] !== '') {
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