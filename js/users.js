$(document).ready(function() { // зaпускaем скрипт пoсле зaгрузки всех элементoв
    
    $(document).on('click', '.btn-add-user', function(e){
        e.preventDefault();
        console.log("Добавляем нового пользователя");
        var $form = $(e.target).parent('form'),
            formData = $form.serialize();

        $.ajax({
            url: '/add_user',
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
    $(document).on('click', '.remove-user', function(e){
        e.preventDefault();
        var userID = parseInt($(e.target).attr('data-id'));
        $.ajax({
            url: '/rm_user',
            type: 'post',
            data: 'id='+userID,
            success: function(resp) {
                console.log("Пришёл ответ", resp);
                var rsp = JSON.parse(resp);
                if (rsp.status === 200) {
                    $(e.target).closest('tr').remove();
                }
            }
        });
    });
    $('#add_user').on('hidden.bs.modal', function (e) {
        $('#add_user').find('input[type="text"],input[type="password"]').val("");
        $('#add_user').find('input[type="checkbox"]').attr('checked',false);
        $('.status-text').removeClass('text-success').removeClass('text-danger').hide();
    })

});