<?
include('header.php');



if (isset($test_data) && $test_data !== '') {
	print_r($test_data);
}
else {
	echo "<h2>Выберите тест для прохождения</h2>";

	$query = mysqli_query($link, "SELECT * FROM tests");

	echo "<div class='list-group'>";

	while ($tests = mysqli_fetch_array($query)) {
		echo "
			<a href='?test=$tests[id]' class='list-group-item'>$tests[name]</a>
		";
	}

	echo "</div><hr>";
}


include('footer.php');
?>