<? include("header.php"); ?>
	<? 
	$query = mysqli_query($link, "SELECT results_test.id, results_test.id_test, results_test.id_user, results_test.result, results_test.ocenka, tests.name, users.fio FROM results_test, tests, users  WHERE tests.id = results_test.id_test AND users.id = results_test.id_user");
	$filter = mysqli_query($link, "SELECT * FROM  tests");
	$i = 0;
	?>
	<div class="results">
		<h1>Результаты</h1>
		<div class="col-sm-4">
			<select class="form-control filter" id="filter">
				<option value="" selected>Все</option>
			<?
				while($item = mysqli_fetch_array($filter)){
					?>
					<option value="<? echo $item['id'] ?>"><? echo $item['name']; ?></option>
					<?
				}
			?>	
			</select>
		</div>
		<div class="row">
			<div class="modal fade" id="view_result">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Результат тестирования</h4>
						</div>
						<div class="modal-body">
							<p class="status-text text-center"><b></b></p>
							<div class="container-fluid">
								<div class="row">
									<!-- ТУТ ДОБАВИТЬ РЕЗУЛЬТАТЫ  -->
									<h1>123</h1>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="table-responsive">
			<table class="table table-hover list_results">
				<thead>
					<tr>
						<th style="font-size: 18px;"><b>№</b></th>
						<th style="font-size: 18px;"><b>ФИО</b></th>
						<th style="font-size: 18px;"><b>Тест</b></th>
						<th style="font-size: 18px;"><b>Результат</b></th>
						<th style="font-size: 18px;"><b>Оценка</b></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<? while($result = mysqli_fetch_array($query)){ 
						$i = $i + 1;
						echo "<tr class='item' data-test-id ='$result[id_test]'><td>$i</td><td>$result[fio]</td><td>$result[name]</td><td>$result[result] %</td><td>$result[ocenka]</td><td><span class='glyphicon glyphicon-remove remove-result' aria-hidden='true' data-id='$result[id]'></span></td></tr>";
					} ?>
				</tbody>
			</table>
		</div>
	</div>
	<script src="../js/results_test.js"></script>
<?php include("footer.php"); ?>