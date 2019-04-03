$(document).ready(function() { // зaпускaем скрипт пoсле зaгрузки всех элементoв

    if($('input#radio').prop("checked")){
        $('.form-group.radio').css('display', 'block');
        $('.form-group.check').css('display', 'none');
        $('.form-group.word').css('display', 'none');
    }else if($('input#check').prop("checked")){
        $('.form-group.radio').css('display', 'none');
        $('.form-group.check').css('display', 'block');
        $('.form-group.word').css('display', 'none');
    }else{
        $('.form-group.radio').css('display', 'none');
        $('.form-group.check').css('display', 'none');
        $('.form-group.word').css('display', 'block');
    }

    $('.type-answer').on('change', function(){
        if($('input#radio').prop("checked")){
            $('.form-group.radio').css('display', 'block');
            $('.form-group.check').css('display', 'none');
            $('.form-group.word').css('display', 'none');
        }else if($('input#check').prop("checked")){
            $('.form-group.radio').css('display', 'none');
            $('.form-group.check').css('display', 'block');
            $('.form-group.word').css('display', 'none');
        }else{
            $('.form-group.radio').css('display', 'none');
            $('.form-group.check').css('display', 'none');
            $('.form-group.word').css('display', 'block');
        } 
    });

    $(document).on('click', '.btn-add-question', function(e){
        e.preventDefault();
        console.log("Добавляем новый вопрос");
        var $form = $(e.target).parent('form'),
            formData = $form.serialize(), $url;

        if ($form.find('#edited').val() !== '') {
            $url = '/save_question';
            console.log("Сохранаяем новую информацию");
        }
        else {
            $url = '/add_question';
            console.log("Добавляем новый вопрос");
        }
        $.ajax({
            url: $url,
            type: 'post',
            data: formData,
            success: function(resp) {
                console.log("Пришёл ответ", resp);
                var rsp = JSON.parse(resp);
                if (rsp.status === 200) {
                    $form.closest('.modal-body').find('.status-text').addClass('text-success').find('b').text(rsp.text);
                    $form.closest('.modal-body').find('.status-text').show(200);
                    if ($form.find('#edited').val() === '') {
                        var question = rsp.question;
                        var count = $('.list_questions tbody tr').length+1;
                        var newTR = `<tr>
                                        <td>${count}</td>
                                        <td>${question.question}</td>
                                        <td>${question.parent_test}</td>
                                        <td>
                                            <button class='glyphicon glyphicon-pencil edit-question' aria-hidden='true' data-id='${question.id}'></button>
                                        </td>
                                        <td>
                                            <span class='glyphicon glyphicon-remove remove-question' aria-hidden='true' data-id='${question.id}'></span>
                                        </td>
                                    </tr>`;
                        $('.list_questions').append(newTR);
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
     * Функция для удаления теста
     */
    $(document).on('click', '.remove-question', function(e){
        e.preventDefault();
        var questionID = parseInt($(e.target).attr('data-id'));
        $.ajax({
            url: '/rm_question',
            type: 'post',
            data: 'id='+questionID,
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
        console.log("Редактирование вопроса");
        var $id = parseInt($(e.target).attr('data-id'));

        $.ajax({
            url: '/ed_question',
            type: 'post',
            data: 'id='+$id,
            success: function(resp) {
                console.log("Пришёл ответ", resp);
                var rsp = JSON.parse(resp);
                if (rsp.status === 200) {
                    var $question = rsp.question;
                    $('#add_question').find('#id_question').val($question.id);
                    $('#add_question').find('#edited').val(1);
                    $('#add_question').find('#parent_test').val($question.parent_test);
                    $('#add_question').find('#name').val($question.question);
                    $('#add_question').modal('show');
                }
            }
        });

    });

    /**
     * Функция для очистки полей после закрытия модального окна
     */
    $('#add_question').on('hidden.bs.modal', function (e) {
        $('#add_question').find('input[type="text"],input[type="hidden"]').val("");
        $('#add_question').find('input[type="checkbox"]').prop('checked',false);
        $('.status-text').removeClass('text-success').removeClass('text-danger').hide();
    })

});