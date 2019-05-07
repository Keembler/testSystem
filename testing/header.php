<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Тестирование</title>
	<!-- Latest compiled and minified CSS & JS -->
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<script src="../js/jquery.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/testirovanie.js"></script>
</head>
<body>
	<div class="testing">
		<div class="container-fluid">
			<div class="row testirovanie-header">
				<? if($role != 'Студент'){ ?>
				<div class="col-md-4 left">
					<a href="/adminka" target="_blank" class="site-name">Перейти в панель администратора</a>
				</div>
				<? } ?>
				<div class="col-md-5 col-md-offset-3 right text-right">
					<div class="info">Вы вошли как <? echo "$fio"; ?></div>
					<a href="/adminpanel/exit.php" class="logout">Выйти</a>
				</div>
			</div>	<!-- .header -->
		    <div class="row">
		        <div class="col-sm col-sm-8 content testirovanie">
