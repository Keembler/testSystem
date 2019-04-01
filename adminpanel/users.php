<? include("header.php"); ?>
	<? 
	$query = mysqli_query($link, "SELECT * FROM users");
	$i = 0;
	?>
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
							<p class="status-text text-center"><b></b></p>
							<div class="container-fluid">
								<div class="row">
									<form role="form" class="add-user-form">
										<input type="hidden" name="id_user" id="id_user" value="">
										<input type="hidden" name="edited" id="edited" value="">
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
												<input type="checkbox" value="1" name="root" id="root">
												Права администратора
											</label>
										</div>
										<button type="submit" class="btn btn-primary btn-add-user">Сохранить</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" id="ed_user">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Редактирование пользователя</h4>
						</div>
						<div class="modal-body">
							<p class="status-text text-center"><b></b></p>
							<div class="container-fluid">
								<div class="row">
									<form role="form" class="edit-user-form">
										<div class="form-group">
											<label for="fio">ФИО</label>
											<input type="text" class="form-control" id="fio" name="fio" placeholder="ФИО" >
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
										<button type="submit" class="btn btn-primary btn-add-user">Сохранить</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="table-responsive">
			<table class="table table-hover list_users">
				<thead>
					<tr>
						<th style="font-size: 18px;"><b>№</b></th>
						<th style="font-size: 18px;"><b>Логин</b></th>
						<th style="font-size: 18px;"><b>Роль</b></th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<? while($user = mysqli_fetch_array($query)){ 
						$i = $i + 1;
						echo "<tr><td>$i</td><td>$user[login]</td><td>$user[role]</td><td><button class='glyphicon glyphicon-pencil edit-user' aria-hidden='true' data-id='$user[id]'></button></td><td><span class='glyphicon glyphicon-remove remove-user' aria-hidden='true' data-id='$user[id]'></span></td></tr>";
					}?>
				</tbody>
			</table>
		</div>
	</div>
	<script src="../js/users.js"></script>
<?php include("footer.php"); ?>