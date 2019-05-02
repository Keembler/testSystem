<? include("header.php"); ?>
	<? 
	$query = mysqli_query($link, "SELECT * FROM results_test");
	$i = 0;
	?>
	<div class="results">
		<h1>Результаты</h1>
		<div class="row">
			<div class="modal fade" id="add_result">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						</div>
						<div class="modal-body">
							<p class="status-text text-center"><b></b></p>
							<div class="container-fluid">
								<div class="row">
									<!-- ТУТ ДОБАВИТЬ РЕЗУЛЬТАТЫ  -->
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
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<? while($result = mysqli_fetch_array($query)){ 
						$i = $i + 1;
						echo "<tr><td>$i</td><td>$result[fio]</td><td>$result[test]</td><td>$result[result]</td><td><button class='glyphicon glyphicon-pencil edit-result' aria-hidden='true' data-id='$result[id]'></button></td><td><span class='glyphicon glyphicon-remove remove-result' aria-hidden='true' data-id='$result[id]'></span></td></tr>";
					} ?>
				</tbody>
			</table>
		</div>
	</div>
	<script src="../js/results.js"></script>
<?php include("footer.php"); ?>