<? include("header.php"); ?>
	<? 
	$query = mysqli_query($link, "SELECT * FROM polls");
	$i = 0;
	?>
	<div class="polls">
		<h1>Опросы</h1>
		<div class="row">
			<button class="btn btn-primary" data-toggle="modal" href='#add_poll'>Добавить</button>
			<div class="modal fade" id="add_poll">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Новый опрос</h4>
						</div>
						<div class="modal-body">
							<p class="status-text text-center"><b></b></p>
							<div class="container-fluid">
								<div class="row">
									<form role="form" class="add-poll-form">
										<input type="hidden" name="id_poll" id="id_poll" value="">
										<input type="hidden" name="edited" id="edited" value="">
										<div class="form-group">
											<label for="poll-name">Название опроса</label>
											<input type="text" class="form-control" id="name" name="name" placeholder="Название">
										</div>
										<div class="checkbox">
											<label>
												<input type="checkbox" name="enable" id="enable" value="1">
												Активен
											</label>
										</div>
										<button type="submit" class="btn btn-primary btn-add-poll">Сохранить</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="table-responsive">
			<table class="table table-hover list_polls">
				<thead>
					<tr>
						<th style="font-size: 18px;"><b>№</b></th>
						<th style="font-size: 18px;"><b>Название</b></th>
						<th style="font-size: 18px;"><b>Активен</b></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<? while($poll = mysqli_fetch_array($query)){ 
						$i = $i + 1;
						echo "<tr><td>$i</td><td>$poll[name]</td><td>$poll[enable]</td><td><button class='glyphicon glyphicon-eye-open open-result-poll' aria-hidden='true' data-id='$poll[id]' data-name='$poll[name]'></button></td><td><button class='glyphicon glyphicon-pencil edit-poll' aria-hidden='true' data-id='$poll[id]'></button></td><td><span class='glyphicon glyphicon-remove remove-poll' aria-hidden='true' data-id='$poll[id]'></span></td></tr>";
					}?>
				</tbody>
			</table>
		</div>
		<div class="modal fade" id="poll_results">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Результаты отпроса</h4>
					</div>
					<div class="modal-body">
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="../js/polls.js"></script>
<?php include("footer.php"); ?>