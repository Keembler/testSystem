$(document).ready(function() { // зaпускaем скрипт пoсле зaгрузки всех элементoв

    var filter_select_el = document.getElementById('filter');
    var items_el = document.querySelector('.list_questions tbody');

    filter_select_el.onchange = function() {
        if(this.value == ''){
            $('tr.item').css('display', 'table-row');
            return;
        }
      var items = items_el.getElementsByClassName('item');
      for (var i=0; i<items.length; i++) {
        if (parseInt($(items[i]).attr('data-test-id')) == parseInt(this.value) ) {
            items[i].style.display = 'table-row';
        } else {
            items[i].style.display = 'none';
        }
      }
    };
    
    function clearInput () {
        $('#add_question').find('input[type="text"],input[type="hidden"]').val("");
        $('#add_question').find('input[type="checkbox"]').prop('checked',false);
        $('#add_question').find('input[id="radio"]').prop('checked',false);
        $('#add_question').find("#image-question").val("");
    }
    function changeTypeAnswer () {
        if($('input#type_radio').prop("checked")){
            $('.form-group.radio').css('display', 'block').addClass('active_block_answers');
            $('.form-group.check').css('display', 'none').removeClass('active_block_answers');
            $('.form-group.word').css('display', 'none').removeClass('active_block_answers');
        }else if($('input#type_check').prop("checked")){
            $('.form-group.radio').css('display', 'none').removeClass('active_block_answers');
            $('.form-group.check').css('display', 'block').addClass('active_block_answers');
            $('.form-group.word').css('display', 'none').removeClass('active_block_answers');
        }else if($('input#type_word').prop("checked")){
            $('.form-group.radio').css('display', 'none').removeClass('active_block_answers');
            $('.form-group.check').css('display', 'none').removeClass('active_block_answers');
            $('.form-group.word').css('display', 'block').addClass('active_block_answers');
        }
    }

    function add_answer_radio (count = 1) {
        if (count > 1) {
            for (var i = 0; i < count; i++) {
                $('.form-group.radio .input-groups').append("<div class='input-group'><span class='input-group-addon'><input type='radio' id='radio' name='correct_answer'></span><input type='text' class='form-control' name='answer'></div>");
            }
        }
        else {
            $('.form-group.radio .input-groups').append("<div class='input-group'><span class='input-group-addon'><input type='radio' id='radio' name='correct_answer'></span><input type='text' class='form-control' name='answer'></div>");
        }
    }
    function add_answer_check (count = 1) {
        if (count > 1) {
            for (var i = 0; i < count; i++) {
                $('.form-group.check .input-groups').append("<div class='input-group'><span class='input-group-addon'><input type='checkbox' name='correct_answer'></span><input type='text' class='form-control' name='answer'></div>");
            }
        }
        else {
            $('.form-group.check .input-groups').append("<div class='input-group'><span class='input-group-addon'><input type='checkbox' name='correct_answer'></span><input type='text' class='form-control' name='answer'></div>");
        }
        
    }
    $('.add-answer-radio').on('click', function(){
        add_answer_radio();
    })
    $('.add-answer-check').on('click', function(){
        add_answer_check();
    })
    $('.remove-answer-radio').on('click', function(){
        $('.form-group.radio .input-groups > .input-group:last-child').remove();
    })
    $('.remove-answer-check').on('click', function(){
        $('.form-group.check .input-groups > .input-group:last-child').remove();
    })

    changeTypeAnswer();

    $('.type-answer').on('change', function(){
        changeTypeAnswer();
    });

    $(document).on('click', '.btn-add-question', function(e){
        e.preventDefault();

        var ANSWERS = [];

        var $form = $(e.target).parent('form'),
            formData, $url;

        var testID = $form.find('#parent_test').val(),
            question = $form.find('[name="question"]').val(),
            questionID = $form.find('[name="id_question"]').val();
            type_answer = $form.find('[name="type_answer"]:checked').val();

        $('.active_block_answers .input-group').each(function(ind,inp_group){
            
            if ($(inp_group).find('input[name="answer"]').length > 0 && $(inp_group).find('input[name="answer"]').val() !== '') {
                var answer = {
                    id: $(inp_group).find('input[name="answer"]').attr('data-id') ? $(inp_group).find('input[name="answer"]').attr('data-id') : 0,
                    answer: $(inp_group).find('input[name="answer"]').val(),
                    correct_answer: $(inp_group).find('input[name="correct_answer"]').prop('checked') ? 1 : 0
                }
                ANSWERS.push(answer);
                
            } else if($(inp_group).find('input[name="answer_word"]').length > 0 && $(inp_group).find('input[name="answer_word"]').val() !== '') {
               var answer = {
                    id: $(inp_group).find('input[name="answer_word"]').attr('data-id') ? $(inp_group).find('input[name="answer_word"]').attr('data-id') : 0,
                    answer: $(inp_group).find('input[name="answer_word"]').val(),
                    correct_answer: $(inp_group).find('input[name="correct_answer_word"]').prop('checked') ? 1 : 0
                }
                ANSWERS.push(answer);
            }
        });
        // создадим объект данных формы
        var data = new FormData(),
            files = document.getElementById('image-question').files[0];

        data.append('files', files );
        data.append('parent_test',testID);
        data.append('type_answer',type_answer);
        data.append('id_question',questionID);
        data.append('question',question);
        data.append('answers',JSON.stringify(ANSWERS));

        if ($form.find('#edited').val() !== '') {
            $url = '/save_question';
        }
        else {
            $url = '/add_question';
        }
        $.ajax({
            url: $url,
            type: 'post',
            data: data,     
            // отключаем обработку передаваемых данных, пусть передаются как есть
            processData: false,
            // отключаем установку заголовка типа запроса. Так jQuery скажет серверу что это строковой запрос
            contentType: false, 
            success: function(resp) {
                console.log(resp);
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
                                        <td>${question.parent_test}</td>
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
     * Функция получения информации о вопросе для редактирования
     */
    $(document).on('click', '.glyphicon-pencil', function(e){
        e.preventDefault();
        var $id = parseInt($(e.target).attr('data-id'));

        $.ajax({
            url: '/ed_question',
            type: 'post',
            data: 'id='+$id,
            success: function(resp) {
                var rsp = JSON.parse(resp);
                console.log(rsp);
                if (rsp.status === 200) {
                    var $question = rsp.question,
                        $answers = rsp.answers,
                        modal_add_q = $('#add_question');

                    modal_add_q.find('.modal-title').text('Редактирование');
                    modal_add_q.find('#id_question').val($question.id);
                    modal_add_q.find('#edited').val(1);
                    modal_add_q.find('#parent_test').val($question.parent_test);
                    modal_add_q.find('#name').val($question.question);
                    modal_add_q.find('#type-answer').val($question.type_answer);
                    modal_add_q.find('#type_'+$question.type_answer).prop('checked',true);
                    modal_add_q.find('#type_'+$question.type_answer).trigger('change');

                    if ($answers.length > 2 && modal_add_q.find('.form-group.'+$question.type_answer+' .input-group').length <= 2) {
                        if ($question.type_answer == 'radio') {
                            add_answer_radio($answers.length-2);
                        }
                        else if ($question.type_answer == 'check') {
                            add_answer_check($answers.length-2);
                        }
                    }
                    
                    
                    modal_add_q.find('.form-group.'+$question.type_answer+' .input-group').each(function(i){
                        var answer = $(this);
                        $answers[i].correct_answer === 1 && answer.find('[name="correct_answer"]').prop('checked',true);
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
    $('#add_question').on('hidden.bs.modal', function (e) {
        clearInput();
        $('#add_question').find('input[type="radio"]').prop('checked',false);
        $('.status-text').removeClass('text-success').removeClass('text-danger').hide();
        $('#add_question').find('.modal-title').text('Новый вопрос');
        $('#add_question').find('#edited').val('');
    })

});