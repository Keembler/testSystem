$(document).ready(function() { // зaпускaем скрипт пoсле зaгрузки всех элементoв

    function clearInput () {
        $('#add_question_poll').find('input[type="text"],input[type="hidden"]').val("");
    }

    function add_answer_radio (count = 1) {
        if (count > 1) {
            for (var i = 0; i < count; i++) {
                $('.form-group .input-groups').append("<div class='input-group'><input type='text' class='form-control' name='answer'></div>");
            }
        }
        else {
            $('.form-group .input-groups').append("<div class='input-group'><input type='text' class='form-control' name='answer'></div>");
        }
    }
    $('.add-answer-radio').on('click', function(){
        add_answer_radio();
    })
    $('.remove-answer-radio').on('click', function(){
        $('.form-group .input-groups > .input-group:last-child').remove();
    })

    $(document).on('click', '.btn-add-question', function(e){
        e.preventDefault();

        var ANSWERS = [];

        console.log("Добавляем новый вопрос");

        var $form = $(e.target).parent('form'),
            formData, $url;

        var pollID = $form.find('#parent_poll').val(),
            question = $form.find('[name="question"]').val(),
            questionID = $form.find('[name="id_question"]').val();

        $('.input-groups .input-group').each(function(ind,inp_group){
            
            if ($(inp_group).find('input[name="answer"]').length > 0 && $(inp_group).find('input[name="answer"]').val() !== '') {
                var answer = {
                    id: $(inp_group).find('input[name="answer"]').attr('data-id') ? $(inp_group).find('input[name="answer"]').attr('data-id') : 0,
                    answer: $(inp_group).find('input[name="answer"]').val()
                }
                ANSWERS.push(answer);
                
            }
        });
        console.log("ANSWERS", ANSWERS);
        
        formData = 'parent_poll='+pollID+'&id_question='+questionID+'&question='+question+'&answers='+JSON.stringify(ANSWERS);

        if ($form.find('#edited').val() !== '') {
            $url = '/save_question_poll';
            console.log("Сохранаяем новую информацию");
        }
        else {
            $url = '/add_question_poll';
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
                        console.log(question);
                        var count = $('.list_questions tbody tr').length+1;
                        var newTR = `<tr>
                                        <td>${count}</td>
                                        <td>${question.question}</td>
                                        <td>${question.parent_poll}</td>
                                        <td>
                                            <button class='glyphicon glyphicon-pencil edit-question' aria-hidden='true' data-id='${question.id}'></button>
                                        </td>
                                        <td>
                                            <span class='glyphicon glyphicon-remove remove-question' aria-hidden='true' data-id='${question.id}'></span>
                                        </td>
                                    </tr>`;
                        $('.list_questions').append(newTR);
                        clearInput();
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
     * Функция для удаления вопроса
     */
    $(document).on('click', '.remove-question', function(e){
        e.preventDefault();
        var questionID = parseInt($(e.target).attr('data-id'));
        $.ajax({
            url: '/rm_question_poll',
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
     * Функция получения информации о вопросе для редактирования
     */
    $(document).on('click', '.glyphicon-pencil', function(e){
        e.preventDefault();
        console.log("Редактирование вопроса");
        var $id = parseInt($(e.target).attr('data-id'));

        $.ajax({
            url: '/ed_question_poll',
            type: 'post',
            data: 'id='+$id,
            success: function(resp) {
                console.log("Пришёл ответ", resp);
                var rsp = JSON.parse(resp);
                console.log(rsp);
                if (rsp.status === 200) {
                    var $question = rsp.question,
                        $answers = rsp.answers,
                        modal_add_q = $('#add_question_poll');

                    modal_add_q.find('.modal-title').text('Редактирование');
                    modal_add_q.find('#id_question').val($question.id);
                    modal_add_q.find('#edited').val(1);
                    modal_add_q.find('#parent_poll').val($question.parent_poll);
                    modal_add_q.find('#name').val($question.question);
                    modal_add_q.find('.form-group .input-group').each(function(i){
                        var answer = $(this);
                        answer.find('[name="answer"]').val($answers[i].answer);
                        answer.find('[name="answer"]').attr('data-id',$answers[i].id);
                    })

                    modal_add_q.modal('show');
                }
            }
        });

    });

    /**
     * Функция для очистки полей после закрытия модального окна
     */
    $('#add_question_poll').on('hidden.bs.modal', function (e) {
        clearInput();
        $('#add_question_poll').find('input[type="radio"]').prop('checked',false);
        $('.status-text').removeClass('text-success').removeClass('text-danger').hide();
        $('#add_question_poll').find('.modal-title').text('Новый вопрос');
        $('#add_question_poll').find('#edited').val('');
    })

});