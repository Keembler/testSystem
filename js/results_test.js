$(document).ready(function() { // зaпускaем скрипт пoсле зaгрузки всех элементoв

    /**
     * Функция для удаления результата
     */
    $(document).on('click', '.remove-result', function(e){
        e.preventDefault();
        var resultID = parseInt($(e.target).attr('data-id'));
        $.ajax({
            url: '/rm_result',
            type: 'post',
            data: 'id='+resultID,
            success: function(resp) {
                var rsp = JSON.parse(resp);
                if (rsp.status === 200) {
                    $(e.target).closest('tr').remove();
                }
            }
        });
    });

    /**
     * Функция получения информации о результате
     */
    $(document).on('click', '.glyphicon-eye-open', function(e){
        e.preventDefault();
        var $id = parseInt($(e.target).attr('data-id'));

        $.ajax({
            url: '/view_result',
            type: 'post',
            data: 'id='+$id,
            success: function(resp) {
                console.log("Пришёл ответ", resp);
                var rsp = JSON.parse(resp);
                console.log(rsp);
                if (rsp.status === 200) {
                    var $question = rsp.question,
                        modal_view_r = $('#view_result');

                    modal_view_r.modal('show');
                }
            }
        });

    });

});