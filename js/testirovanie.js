$(document).ready(function() {
	$('#btn').on('click', function(){
		var test = $('#test-id').text();
		var res = {'test':test};

		$('.question').each(function(){
			var id = $(this).data('id');
			if ($('input[name=question-'+ id +']:checked').length > 1) {
				$('input[name=question-'+ id +']:checked').each(function(index){
					index == 0 ? res[id] = $(this).val() : res[id] += ','+$(this).val();
				});
			}
			else {
				res[id] = $('input[name=question-'+ id +']:checked').length > 0 ? $('input[name=question-'+ id +']:checked').val() : $('input[type="text"][name=question-'+ id +']').val();
			}
		});

		$.ajax({
			url: '/testirovanie',
			type: 'post',
			data: res,
			success: function(resp){
				$('.content').html(resp);
			},
			error: function(){
				alert('Ошибка');
			}
		});
	})
})