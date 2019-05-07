<? include("header.php"); ?>
	<? 
	$query = mysqli_query($link, "SELECT questions_polls.id, questions_polls.question, polls.name FROM questions_polls, polls  WHERE polls.id = questions_polls.parent_poll");
	$i = 0;
	?>
	<div class="questions">
		<h1>Вопросы опросов</h1>
		<div class="row">
			<button class="btn btn-primary" data-toggle="modal" href='#add_question_poll'>Добавить</button>
			<div class="modal fade" id="add_question_poll">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Новый вопрос</h4>
						</div>
						<div class="modal-body">
							<p class="status-text text-center"><b></b></p>
							<div class="container-fluid">
								<div class="row">
									<form role="form" class="add-question-form">	<input type="hidden" name="id_question" id="id_question" value="">
										<input type="hidden" name="edited" id="edited" value="">
										<div class="form-group">
											<label for="role">Опрос</label>
											<select name="parent_poll" id="parent_poll" class="form-control" required="required">
												<?
													$query_poll = mysqli_query($link, "SELECT * FROM polls");
													while ($poll = mysqli_fetch_array($query_poll)) {
												?>
												<option value="<? echo $poll['id']; ?>"><? echo $poll['name']; ?></option>
												<? } ?>
											</select>
										</div>
										<div class="form-group">
											<label for="question-name">Вопрос</label>
											<input type="text" class="form-control" id="name" name="question" placeholder="Вопрос">
										</div>
										<div class="form-group">
											<div class="title">Впишите варианты ответов</div>
											<div class="input-groups">
												<div class="input-group">
													<input type="text" class="form-control" name="answer">
												</div>
												<div class="input-group">
													<input type="text" class="form-control" name="answer">
												</div>
											</div>
											<a href="javascript:void(0)" class='glyphicon glyphicon-plus add-answer-radio'></a>
											<a href="javascript:void(0)" class='glyphicon glyphicon-remove remove-answer-radio'></a>
										</div>
										<button type="submit" class="btn btn-primary btn-add-question">Сохранить</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="table-responsive">
			<table class="table table-hover list_questions">
				<thead>
					<tr>
						<th style="font-size: 18px;"><b>№</b></th>
						<th style="font-size: 18px;"><b>Вопрос</b></th>
						<th style="font-size: 18px;"><b>Опрос</b></th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<? while($question = mysqli_fetch_array($query)){ 
						$i = $i + 1;
						echo "<tr><td>$i</td><td>$question[question]</td><td>$question[name]</td><td><button class='glyphicon glyphicon-pencil edit-question' aria-hidden='true' data-id='$question[id]'></button></td><td><span class='glyphicon glyphicon-remove remove-question' aria-hidden='true' data-id='$question[id]'></span></td></tr>";
					} ?>
				</tbody>
			</table>
		</div>
	</div>
	<script src="../js/questions-polls.js"></script>
<?php include("footer.php"); ?>