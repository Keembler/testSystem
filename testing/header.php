<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Тестирование</title>
	<!-- Latest compiled and minified CSS & JS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="../css/style.css">
	<script src="../js/testirovanie.js"></script>
</head>
<body>
	<div class="testing">
		<div class="container-fluid">
			<div class="row testirovanie-header">
				<div class="col-md-4 left">
					<a href="/adminka" target="_blank" class="site-name">Перейти в панель администратора</a>
				</div>
				<div class="col-md-5 col-md-offset-3 right text-right">
					<div class="info">Вы вошли как <?php echo "$fio"; ?></div>
					<a href="/adminpanel/exit.php" class="logout">Выйти</a>
				</div>
			</div>	<!-- .header -->
		    <div class="row">
		        <div class="col-sm col-sm-8 content testirovanie">
