<? include("header.php"); ?>
	<? 
	$query = mysqli_query($link, "SELECT * FROM tests");
	$i = 0;
	?>
	<div class="tests">
		<h1>Тесты</h1>
		<div class="row">
			<button class="btn btn-primary" data-toggle="modal" href='#add_test'>Добавить</button>
			<div class="modal fade" id="add_test">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Новый тест</h4>
						</div>
						<div class="modal-body">
							<p class="status-text text-center"><b></b></p>
							<div class="container-fluid">
								<div class="row">
									<form role="form" class="add-test-form">
										<input type="hidden" name="id_test" id="id_test" value="">
										<input type="hidden" name="edited" id="edited" value="">
										<div class="form-group">
											<label for="test-name">Название теста</label>
											<input type="text" class="form-control" id="name" name="name" placeholder="Название">
										</div>
										<div class="form-group">
											<label for="test-time">Время на выполнение теста(указывать в минутах)</label>
											<input type="text" class="form-control" id="time" name="time" placeholder="Время на выполнение теста">
										</div>
										<div class="checkbox">
											<label>
												<input type="checkbox" name="enable" id="enable" value="1">
												Активен
											</label>
										</div>
										<div class="checkbox">
											<label>
												<input type="checkbox" name="correct" id="correct" value="1">
												Показывать ответы
											</label>
										</div>
										<button type="submit" class="btn btn-primary btn-add-test">Сохранить</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="table-responsive">
			<table class="table table-hover list_tests">
				<thead>
					<tr>
						<th style="font-size: 18px;"><b>№</b></th>
						<th style="font-size: 18px;"><b>Название</b></th>
						<th style="font-size: 18px;"><b>Время на выполнение</b></th>
						<th style="font-size: 18px;"><b>Активен</b></th>
						<th style="font-size: 18px;"><b>Показывать ответы</b></th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<? while($test = mysqli_fetch_array($query)){ 
						$i = $i + 1;
						if($test['enable'] == 1){
							$enable = 'Да';
						}else{
							$enable = 'Нет';
						}
						if($test['correct'] == 1){
							$correct = 'Да';
						}else{
							$correct = 'Нет';
						}
						echo "<tr><td>$i</td><td>$test[name]</td><td>$test[time] мин.</td><td>$enable</td><td>$correct</td><td><button class='glyphicon glyphicon-pencil edit-test' aria-hidden='true' data-id='$test[id]'></button></td><td><span class='glyphicon glyphicon-remove remove-test' aria-hidden='true' data-id='$test[id]'></span></td></tr>";
					}?>
				</tbody>
			</table>
		</div>
	</div>
	<script src="../js/tests.js"></script>
<?php include("footer.php"); ?>