$(document).ready(function() {
	$url = '/testirovanie';
	$('#btn').on('click', function(){
		var test = $('#test-id').text();
		var res = {'test':test};

		$('.question').each(function(){
			var id = $(this).data('id');
			res[id] = $('input[name=question-'+ id +']:checked').val();
		});

		$.ajax({
			url: $url,
			type: 'post',
			data: res,
			success: function(resp){
				//$('.content').html(resp);
				console.log(resp);
			},
			error: function(){
				alert('Ошибка');
			}
		});
	})
})