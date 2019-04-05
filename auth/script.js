$(function(){
	$('form[name="form"]').on('submit', function(e){
		e.preventDefault();
		var $form = $(this);
		var formData = $form.serialize();
		$.ajax({
			url : '/',
			type: 'POST',
			data: formData+'&login_form=1',
			success: function(resp) {
				console.log(JSON.parse(resp));
				var status = JSON.parse(resp).status;
				if (status === 200) {
					window.location = '/adminka';
				}else if (status ===201) {
					window.location = '/testirovanie';
				}
			}
		});
	})
})