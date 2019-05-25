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
	<script src="../js/jquery.countdown.js"></script>
	<? if(isset($_GET['test'])){ ?>
	<script>
		$(function(){
			var time;
			$.ajax({
				url: '/testirovanie?time=1&test_id='+ $('#test-id').text(),
				type: 'get',
				success: function(resp){
					time = parseInt(resp);
					$(".digits").countdown({
						image: "../img/digits.png",
						startTime: time < 10 ? "0"+time+":00" : time+":00",
						stepTime: 60,
						format: "hh:mm:ss",
						digitImages: 6,
						digitWidth: 67,
						digitHeight: 90,
						timerEnd: function(){},
						continuous: false,
						start: true
					});
					setTimeout(function(){$('#btn').trigger('click')}, time* 60000);
				}
			});
			$(window).scroll(function() {
			    if($(this).scrollTop() >= 60) {
					$('.testing .timer').addClass('sticky');
				}else{
					$('.testing .timer').removeClass('sticky');
			    }
			 });
		});
	</script>
	<? } ?>
</head>
<body>
	<div class="testing">
		<div class="container-fluid">
			<div class="row testirovanie-header">
				<? if($role != 'Студент'){ ?>
				<div class="col-md-4 left">
					<a href="/main" target="_blank" class="site-name">Перейти в панель администратора</a>
				</div>
				<? } ?>
				<div class="col-md-5 col-md-offset-3 right text-right">
					<div class="info">Вы вошли как <? echo "$fio"; ?></div>
					<a href="/adminpanel/exit.php" class="logout">Выйти</a>
				</div>
			</div>	<!-- .header -->
			<? if(isset($_GET['test'])){ ?>
			<div class="row" id="block-timer">
				<div class="col-sm-12 timer"><div class="digits"></div></div>
			</div>
			<? } ?>
		    <div class="row">
		        <div class="col-sm-8 content testirovanie">
