$(document).ready(function() { // зaпускaем скрипт пoсле зaгрузки всех элементoв
    
    $(document).on('click', '.btn-add-test', function(e){
        e.preventDefault();
        var $form = $(e.target).parent('form'),
            formData = $form.serialize(), $url;

        if ($form.find('#edited').val() !== '') {
            $url = '/save_test';
        }
        else {
            $url = '/add_test';
        }
        $.ajax({
            url: $url,
            type: 'post',
            data: formData,
            success: function(resp) {
                console.log("Пришёл ответ", resp);
                var rsp = JSON.parse(resp);
                if (rsp.status === 200) {
                    $form.closest('.modal-body').find('.status-text').removeClass('text-danger').addClass('text-success').find('b').text(rsp.text);
                    $form.closest('.modal-body').find('.status-text').show(200);
                    if ($form.find('#edited').val() === '') {
                        var test = rsp.test;
                        var count = $('.list_tests tbody tr').length+1;
                        var newTR = `<tr>
                                        <td>${count}</td>
                                        <td>${test.name}</td>
                                        <td>${test.time}</td>
                                        <td>${test.enable}</td>
                                        <td>${test.correct}</td>
                                        <td>
                                            <button class='glyphicon glyphicon-pencil edit-test' aria-hidden='true' data-id='${test.id}'></button>
                                        </td>
                                        <td>
                                            <span class='glyphicon glyphicon-remove remove-test' aria-hidden='true' data-id='${test.id}'></span>
                                        </td>
                                    </tr>`;
                        $('.list_tests').append(newTR);
                    }
                }
                else {
                    $form.closest('.modal-body').find('.status-text').removeClass('text-success').addClass('text-danger').find('b').text(rsp.text);
                    $form.closest('.modal-body').find('.status-text').show(200);
                }
            }
        });

    });

    /**
     * Функция для удаления теста
     */
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

    /**
     * Функция получения информации о тесте для редактирования
     */
    $(document).on('click', '.glyphicon-pencil', function(e){
        e.preventDefault();
        var $id = parseInt($(e.target).attr('data-id'));

        $.ajax({
            url: '/ed_test',
            type: 'post',
            data: 'id='+$id,
            success: function(resp) {
                var rsp = JSON.parse(resp);
                if (rsp.status === 200) {
                    var $test = rsp.test;
                    $('#add_test').find('#id_test').val($test.id);
                    $('#add_test').find('#edited').val(1);
                    $('#add_test').find('#name').val($test.name);
                    $('#add_test').find('#time').val($test.time);
                    if (parseInt($test.enable) === 1) {
                        $('#add_test').find('#enable').prop('checked',true);
                    }
                    if (parseInt($test.correct) === 1) {
                        $('#add_test').find('#correct').prop('checked',true);
                    }
                    $('#add_test').modal('show');
                }
            }
        });

    });

    /**
     * Функция для очистки полей после закрытия модального окна
     */
    $('#add_test').on('hidden.bs.modal', function (e) {
        $('#add_test').find('input[type="text"],input[type="hidden"]').val("");
        $('#add_test').find('input[type="checkbox"]').prop('checked',false);
        $('.status-text').removeClass('text-success').removeClass('text-danger').hide();
    })

});