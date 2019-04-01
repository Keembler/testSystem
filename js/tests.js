$(document).ready(function() { // зaпускaем скрипт пoсле зaгрузки всех элементoв
    
    $(document).on('click', '.btn-add-test', function(e){
        e.preventDefault();
        console.log("Добавляем новый тест");
        var $form = $(e.target).parent('form'),
            formData = $form.serialize();

        $.ajax({
            url: '/add_test',
            type: 'post',
            data: formData,
            success: function(resp) {
                console.log("Пришёл ответ", resp);
                var rsp = JSON.parse(resp);
                if (rsp.status === 200) {
                    $form.closest('.modal-body').find('.status-text').addClass('text-success').find('b').text(rsp.text);
                    $form.closest('.modal-body').find('.status-text').show(200);
                }
                else {
                    $form.closest('.modal-body').find('.status-text').addClass('text-danger').find('b').text(rsp.text);
                    $form.closest('.modal-body').find('.status-text').show(200);
                }
            }
        });

    });
    $(document).on('click', '.remove-test', function(e){
        e.preventDefault();
        var testID = parseInt($(e.target).attr('data-id'));
        $.ajax({
            url: '/rm_test',
            type: 'post',
            data: 'id='+testID,
            success: function(resp) {
                console.log("Пришёл ответ", resp);
                var rsp = JSON.parse(resp);
                if (rsp.status === 200) {
                    $(e.target).closest('tr').remove();
                }
            }
        });
    });
    $('#add_test').on('hidden.bs.modal', function (e) {
        $('#add_test').find('input[type="text"]').val("");
        $('#add_test').find('input[type="checkbox"]').attr('checked',false);
        $('.status-text').removeClass('text-success').removeClass('text-danger').hide();
    })

});