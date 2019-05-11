<?
include('header.php');
$i = 0;

if (isset($poll_data) && $poll_data !== '') {
	?>

	<div class="poll-data">
	<div class="none" id="poll-id"><?=$poll_id?></div>
		
	<?php foreach($poll_data as $id_question => $item): // получаем каждый конкретный вопрос + ответы ?>
		<div class="question" data-id="<?=$id_question?>" id="question-<?=$id_question?>">
			<?php foreach($item as $id_answer => $answer): // проходимся по массиву вопрос/ответы ?>
				<?php if(!$id_answer): // выводим вопрос ?>
					<div class="q header-h2"><span class="count-question"><? $i++; echo $i; ?></span><h2> <?=$answer?> </h2></div>
				<?php else: // выводим варианты ответов ?>
					<p class="a" style="margin-left: 50px;">
						<input type="radio" id="answer-<?=$id_answer?>" name="question-<?=$id_question?>" value="<?=$id_answer?>">
						<label for="answer-<?=$id_answer?>"><?=$answer?></label>
					</p>
				<?php endif; // $id_answer ?>

			<?php endforeach; // $item ?>

		</div> <!-- .question -->

	<?php endforeach; // $poll_data ?>

	<div class="buttons">
		<div class="col-sm-6"><button type="button" id="btn" class="btn btn-lg red">Голосовать</button><a href="../adminpanel/exit.php" id="btn" class="btn btn-lg red" style="margin-left: 30px;">Закончить опрос</a></div>
	</div>

	</div> <!-- .test-data -->
	<?
}
else {
	echo "<h2>Выберите опрос</h2>";

	$query = mysqli_query($link, "SELECT * FROM polls WHERE enable = '1'");

	echo "<div class='list-group'>";

	while ($polls = mysqli_fetch_array($query)) {
		echo "
			<a href='?poll=$polls[id]' class='list-group-item'>$polls[name]</a>
		";
	}

	echo "</div><hr>";
}


include('footer.php');
?>