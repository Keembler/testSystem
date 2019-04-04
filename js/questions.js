$(document).ready(function() { // зaпускaем скрипт пoсле зaгрузки всех элементoв

    

    function changeTypeAnswer () {
        if($('input#radio').prop("checked")){
            $('.form-group.radio').css('display', 'block').addClass('active_block_answers');
            $('.form-group.check').css('display', 'none').removeClass('active_block_answers');
            $('.form-group.word').css('display', 'none').removeClass('active_block_answers');
        }else if($('input#check').prop("checked")){
            $('.form-group.radio').css('display', 'none').removeClass('active_block_answers');
            $('.form-group.check').css('display', 'block').addClass('active_block_answers');
            $('.form-group.word').css('display', 'none').removeClass('active_block_answers');
        }else{
            $('.form-group.radio').css('display', 'none').removeClass('active_block_answers');
            $('.form-group.check').css('display', 'none').removeClass('active_block_answers');
            $('.form-group.word').css('display', 'block').addClass('active_block_answers');
        }
    }

    changeTypeAnswer();

    $('.type-answer').on('change', function(){
        changeTypeAnswer();
    });

    $(document).on('click', '.btn-add-question', function(e){
        e.preventDefault();

        var ANSWERS = [];

        console.log("Добавляем новый вопрос");

        var $form = $(e.target).parent('form'),
            formData, $url;

        var testID = $form.find('#parent_test').val(),
            question = $form.find('[name="question"]').val();

        $('.active_block_answers .input-group').each(function(ind,inp_group){
            
            if ($(inp_group).find('input[name="answer"]').val() !== '') {
                var answer = {
                    answer: $(inp_group).find('input[name="answer"]').val(),
                    correct_answer: $(inp_group).find('input[name="correct_answer"]').prop('checked') ? 1 : 0
                }
                ANSWERS.push(answer);
                
            }
        });
        console.log("ANSWERS", ANSWERS);
        
        formData = 'parent_test='+testID+'&question='+question+'&answers='+JSON.stringify(ANSWERS);

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
                /*var rsp = JSON.parse(resp);
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
                }*/
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