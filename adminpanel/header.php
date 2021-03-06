<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Админ панель</title>
	<!-- Latest compiled and minified CSS & JS -->
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<script src="../js/jquery.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/main.js"></script>
</head>
<body>

	<div class="container-fluid page-wrapper">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="row header">
					<div class="col-md-4 left">
						<a href="/testirovanie" target="_blank" class="site-name">Перейти к тестированию</a>
					</div>
					<div class="col-md-5 col-md-offset-3 right text-right">
						<div class="info">Вы вошли как <?php echo "$fio"; ?></div>
						<a href="/adminpanel/exit.php" class="logout">Выйти</a>
					</div>
				</div>	<!-- .header -->

				<div class="row content-wrapper">
					<nav class="col-md-2 col-sm-2 left-col" role="navigation">
						<!-- Brand and toggle get grouped for better mobile display -->
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar bg-white"></span>
								<span class="icon-bar bg-white"></span>
								<span class="icon-bar bg-white"></span>
							</button>
						</div>
						<!-- Collect the nav links, forms, and other content for toggling -->
						<div class="collapse navbar-collapse navbar-ex1-collapse">
							<ul class="nav">
								<li><a href="/main">Главная</a></li>
								<li><a href="/polls">Опросы</a></li>
								<li><a href="/questions-polls">Вопросы опросов</a></li>
								<li><a href="/tests">Тесты</a></li>
								<li><a href="/questions">Вопросы тестов</a></li>
								<? if($role == 'Администратор'){ ?>
								<li><a href="/users">Пользователи</a></li>
								<? } ?>
								<li><a href="/results-tests">Результаты тестирования</a></li>
							</ul>
						</div><!-- /.navbar-collapse -->
					</nav>
					<div class="col-md-10 col-sm-10 content">
			