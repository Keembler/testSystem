<?
include('header.php');

echo "<h2>Выберите тест для прохождения</h2>";

$query = mysqli_query($link, "SELECT * FROM tests");

echo "<div class='list-group'>";

while ($tests = mysqli_fetch_array($query)) {
	echo "
		<a href='?test=$tests[id]' class='list-group-item'>$tests[name]</a>
	";
}

echo "</div><hr>";

function get_test_data($test_id){
	if(!$test_id) return;
	$query = mysqli_query($link, "SELECT q.question, q.parent_test, a.id, a.answer, a.parent_question FROM questions q LEFT JOIN answers a ON q.id = a.parent_question WHERE q.parent_test = $test_id");
	$data = null;
	while ($row = mysqli_fetch_assoc($query)) {
		$data[$row['parent_question']][0] = $row['question'];
		$data[$row['parent_question']][$row['id']] = $row['answer'];
	}
	return $data;
}

if(isset($_GET['test'])){
	$test_id = (int)$_GET['test'];
	$test_data = get_test_data($test_id);
	echo "$test_data";
}


include('footer.php');
?>