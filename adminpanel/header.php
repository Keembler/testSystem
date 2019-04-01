<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Админ панель</title>
	<!-- Latest compiled and minified CSS & JS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="../css/style.css">
</head>
<body>

	<div class="container-fluid page-wrapper">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="row header">
					<div class="col-md-4 left">
						<a href="../" target="_blank" class="site-name">Перейти к системе тестирования</a>
					</div>
					<div class="col-md-5 col-md-offset-3 right text-right">
						<div class="info">Вы вошли как <?php echo "$role"; ?></div>
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
								<li><a href="/adminka">Главная</a></li>
								<li><a href="/tests">Тесты</a></li>
								<li><a href="/users">Пользователи</a></li>
							</ul>
						</div><!-- /.navbar-collapse -->
					</nav>
					<div class="col-md-10 col-sm-10 content">
			