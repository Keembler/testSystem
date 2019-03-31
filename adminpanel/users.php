<? include("header.php"); ?>
	<? 
	$query = mysqli_query($link, "SELECT * FROM users");
	$i = 0;
	while($user = mysqli_fetch_array($query)){ ?>
	<div class="users">
		<h1>Пользователи</h1>
		<div class="row">
			<button class="btn btn-primary" data-toggle="modal" href='#add_user'>Добавить</button>
			<div class="modal fade" id="add_user">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Новый пользователь</h4>
						</div>
						<div class="modal-body">
							<div class="container-fluid">
								<div class="row">
									<form role="form" class="add-user-form">
										<div class="form-group">
											<label for="fio">ФИО</label>
											<input type="text" class="form-control" id="fio" name="fio" placeholder="ФИО">
										</div>
										<div class="form-group">
											<label for="login">Логин</label>
											<input type="text" class="form-control" id="login" name="login" placeholder="Логин">
										</div>
										<div class="form-group">
											<label for="pass">Пароль</label>
											<input type="text" class="form-control" id="pass" name="pass" placeholder="Пароль">
										</div>
										<div class="form-group">
											<label for="role">Роль</label>
											<select name="role" id="role" class="form-control" required="required">
												<option value="Администратор">Администратор</option>
												<option value="Преподаватель">Преподаватель</option>
												<option value="Сотрудник колледжа">Сотрудник колледжа</option>
												<option value="Студент">Студент</option>
											</select>
										</div>
										
										<div class="checkbox">
											<label>
												<input type="checkbox" value="1">
												Права администратора
											</label>
										</div>
										<button type="submit" class="btn btn-primary">Сохранить</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th style="font-size: 18px;"><b>№</b></th>
						<th style="font-size: 18px;"><b>Логин</b></th>
						<th style="font-size: 18px;"><b>Роль</b></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?
						$i = $i + 1;
						echo "<tr><td>$i</td><td>$user[login]</td><td>$user[role]</td><td><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></td></tr>";
					?>
				</tbody>
			</table>
		</div>
	</div>
	<? } ?>
	<script src="../js/users.js"></script>
<?php include("footer.php"); ?>