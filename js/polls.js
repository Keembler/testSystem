$(document).ready(function() { // зaпускaем скрипт пoсле зaгрузки всех элементoв
    
    $(document).on('click', '.btn-add-poll', function(e){
        e.preventDefault();
        var $form = $(e.target).parent('form'),
            formData = $form.serialize(), $url;

        if ($form.find('#edited').val() !== '') {
            $url = '/save_poll';
            console.log("Сохранаяем новую информацию");
        }
        else {
            $url = '/add_poll';
            console.log("Добавляем новый опрос");
        }
        $.ajax({
            url: $url,
            type: 'post',
            data: formData,
            success: function(resp) {
                var rsp = JSON.parse(resp);
                if (rsp.status === 200) {
                    $form.closest('.modal-body').find('.status-text').addClass('text-success').find('b').text(rsp.text);
                    $form.closest('.modal-body').find('.status-text').show(200);
                    if ($form.find('#edited').val() === '') {
                        var poll = rsp.poll;
                        var count = $('.list_polls tbody tr').length+1;
                        var newTR = `<tr>
                                        <td>${count}</td>
                                        <td>${poll.name}</td>
                                        <td>${poll.enable}</td>
                                        <td>
                                            <button class='glyphicon glyphicon-pencil edit-poll' aria-hidden='true' data-id='${poll.id}'></button>
                                        </td>
                                        <td>
                                            <span class='glyphicon glyphicon-remove remove-poll' aria-hidden='true' data-id='${poll.id}'></span>
                                        </td>
                                    </tr>`;
                        $('.list_polls').append(newTR);
                    }
                }
                else {
                    $form.closest('.modal-body').find('.status-text').addClass('text-danger').find('b').text(rsp.text);
                    $form.closest('.modal-body').find('.status-text').show(200);
                }
            }
        });

    });

    /**
     * Функция для удаления опроса
     */
    $(document).on('click', '.remove-poll', function(e){
        e.preventDefault();
        var pollID = parseInt($(e.target).attr('data-id'));
        $.ajax({
            url: '/rm_poll',
            type: 'post',
            data: 'id='+pollID,
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
     * Функция получения информации о опросе для редактирования
     */
    $(document).on('click', '.glyphicon-pencil', function(e){
        e.preventDefault();
        console.log("Редактирование опроса");
        var $id = parseInt($(e.target).attr('data-id'));

        $.ajax({
            url: '/ed_poll',
            type: 'post',
            data: 'id='+$id,
            success: function(resp) {
                console.log("Пришёл ответ", resp);
                var rsp = JSON.parse(resp);
                if (rsp.status === 200) {
                    var $poll = rsp.poll;
                    $('#add_poll').find('#id_poll').val($poll.id);
                    $('#add_poll').find('#edited').val(1);
                    $('#add_poll').find('#name').val($poll.name);
                    console.log(parseInt($poll.root));
                    if (parseInt($poll.enable) === 1) {
                        $('#add_poll').find('#enable').prop('checked',true);
                    }
                    $('#add_poll').modal('show');
                }
            }
        });

    });

    /**
     * Функция для очистки полей после закрытия модального окна
     */
    $('#add_poll').on('hidden.bs.modal', function (e) {
        $('#add_poll').find('input[type="text"],input[type="hidden"]').val("");
        $('#add_poll').find('input[type="checkbox"]').prop('checked',false);
        $('.status-text').removeClass('text-success').removeClass('text-danger').hide();
    })

});