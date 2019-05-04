<?
ini_set('display_errors',1);
error_reporting(E_ALL);
header("Content-Type: text/html; charset=utf-8");

//ПОДКЛЮЧЕНИЕ К БАЗЕ ДАННЫХ (БД)
include("moduls/db.php");
//ПОДКЛЮЧЕНИЕ К БАЗЕ ДАННЫХ (БД)

session_start();

if ($_SERVER['REQUEST_URI'] == '/') {
	 $page = 'home';
}
else {
	$page = substr($_SERVER['REQUEST_URI'], 1);

/*	if ( !preg_match('/^[A-z0-9\-]{3,25}$/',$page)) {
		
		exit('Not Correct URL: '.$page);
		
	}*/
}

if (isset($_POST['login_form'])) {
	

	//заносим в отдельные переменные логин и пароль присланных с помощью post запроса
	if (isset ($_POST['login'])) {$login = $_POST['login'];}
	if (isset ($_POST['password'])) {$pass = $_POST['password'];}

	if(isset($login) AND isset($pass))//если существуют логин и пароль
	{
		$pass = md5($pass);
		$resultlp = mysqli_query($link, "SELECT * FROM users WHERE login='$login'");//выводим из базы данных логин и пароль
		$log_and_pass = mysqli_fetch_array($resultlp);

		if($log_and_pass != "")//если был выведен результат из БД
		{
			
		    if($login == $log_and_pass['login'] AND $pass == $log_and_pass['password'])//если введенная информация совпадает с информацией из БД
		    {
		        
					$_SESSION['$logSESS'] = $log_and_pass['login'];//создаем глобальную переменную
					$_SESSION['$role'] = $log_and_pass['role'];
					$_SESSION['$id_user'] = $log_and_pass['id'];
					$_SESSION['$fio'] = $log_and_pass['fio'];
					$_SESSION['$root'] = $log_and_pass['root'];
					//header("location: /adminka");
					if($_SESSION['$root'] == 1)print_r('{"status": 200}');
					if($_SESSION['$root'] == 0)print_r('{"status": 201}');
					exit;				
		    }
		    else//если введеная инфо не совпадает с инфо из БД
		    {
		        header("location: /login");//переносим на форму авторизации
		        exit; 				
		    }
		}
		else//если не найдено такого юзера в БД
		{
		    header("location: /login");//переносим на форму авторизации
		    exit;
		}
		
	}
}


//ПРОВЕРКА АВТОРИЗАЦИИ
if (!isset($_SESSION['$logSESS'])) {
  header("location: auth/login.php");
  exit;  
}
if (isset($_SESSION['$role'])) {
	$role = $_SESSION['$role'];
}
if (isset($_SESSION['$fio'])) {
	$fio = $_SESSION['$fio'];
}
if (isset($_SESSION['$id_user'])) {
	$id_user = $_SESSION['$id_user'];
}
//ПРОВЕРКА АВТОРИЗАЦИИ

include('controllers/users.php');
include('controllers/tests.php');
include('controllers/polls.php');
include('controllers/questions.php');
include('controllers/testirovanie.php');
include('controllers/results_test.php');

if(isset($_GET['test']) and $_GET['test'] !== ''){
	$test_id = (int)$_GET['test'];
	$test_data = get_test_data($test_id,$link);
	/*echo "$test_data";*/
	include('testing/testirovanie.php');
	exit;
}

if ( file_exists('adminpanel/'.$page.'.php') and $_SESSION['$root'] == 1 ) { 
	include('adminpanel/'.$page.'.php');
}
else if ( file_exists('auth/'.$page.'.php') ) { 
	include('auth/'.$page.'.php');
}
else if ( file_exists('testing/'.$page.'.php') ) { 
	include('testing/'.$page.'.php');
}
else if ($page === 'home' and $_SESSION['$root'] == 1 ) {
	$page = 'adminka';
	include('adminpanel/'.$page.'.php');
}
