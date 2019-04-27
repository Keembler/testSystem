<?
include('header.php');



if (isset($test_data) && $test_data !== '') {
	?>

	<div class="test-data">
	<div class="none" id="test-id"><?=$test_id?></div>
		
	<?php foreach($test_data as $id_question => $item): // получаем каждый конкретный вопрос + ответы ?>
		<div class="question" data-id="<?=$id_question?>" id="question-<?=$id_question?>">
			<?php foreach($item as $id_answer => $answer): // проходимся по массиву вопрос/ответы ?>
				<?php if(!$id_answer): // выводим вопрос ?>
					<div class="q header-h2"><span class="count_question"></span><h2> <?=$answer?> </h2></div>
				<?php elseif($id_answer !== 'type_answer'): // выводим варианты ответов ?>
					<? if($item['type_answer'] == 'radio'): ?>
					<p class="a" style="margin-left: 50px;">
						<input type="radio" id="answer-<?=$id_answer?>" name="question-<?=$id_question?>" value="<?=$id_answer?>">
						<label for="answer-<?=$id_answer?>"><?=$answer?></label>
					</p>
					<? elseif($item['type_answer'] == 'check'): ?>
						<p class="a" style="margin-left: 50px;">
							<input type="checkbox" id="answer-<?=$id_answer?>" name="question-<?=$id_question?>" value="<?=$id_answer?>">
							<label for="answer-<?=$id_answer?>"><?=$answer?></label>
						</p>
					<? elseif($item['type_answer'] == 'word'): ?>
						<p class="a" style="margin-left: 50px;">
							<input type="text" id="answer-<?=$id_answer?>" name="question-<?=$id_question?>" value="">
						</p>
					<? endif; ?>

				<?php endif; // $id_answer ?>

			<?php endforeach; // $item ?>

		</div> <!-- .question -->

	<?php endforeach; // $test_data ?>

	<div class="buttons">
		 <div class="col-sm-3"><button type="button" id="btn" class="btn btn-lg red">Закончить тест</button></div>
	</div>

	</div> <!-- .test-data -->
	<?
}
else {
	echo "<h2>Выберите тест для прохождения</h2>";

	$query = mysqli_query($link, "SELECT * FROM tests WHERE enable = '1'");

	echo "<div class='list-group'>";

	while ($tests = mysqli_fetch_array($query)) {
		echo "
			<a href='?test=$tests[id]' class='list-group-item'>$tests[name]</a>
		";
	}

	echo "</div><hr>";
}


include('footer.php');
?>