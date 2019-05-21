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
     * Функция получения информации о результатах опроса
     */
    $(document).on('click', '.open-result-poll', function(e){
        e.preventDefault();
        console.log("Получение результатов опроса");
        var $id = parseInt($(e.target).attr('data-id')),
            name_poll = $(e.target).attr('data-name');

        $.ajax({
            url: '/result_poll',
            type: 'post',
            data: 'id='+$id,
            success: function(resp) {
                // console.log("Пришёл ответ", resp);
                var rsp = JSON.parse(resp);
                var polls = rsp.poll;
                // console.log(polls);
                if (rsp.status === 200) {
                    var que = '', lines = '';
                    polls.map(function(item){
                        if (que !== '' && que == item.question) {
                            // lines += '<div class="line-body">'+item.answer+' - '+item.votes+'</div>';
                            item.data.map(function(item_data){
                                lines += '<tr>';
                                lines += '<td>';
                                lines += '<div style="width:460px;">'+item_data.answer;
                                lines += '<div class="votes" style="width:'+(item_data.votes/item.max_v*460);
                                lines += 'px;">'+item_data.votes+'</div></div>';
                                lines += '</td>';
                                lines += '<td>';
                                lines += '<div style="padding: 8px 0 0 20px;"><b>';
                                if (item.sum_v==0) lines += '0 %</div>';
                                else lines += Math.round(100*item_data.votes/item.sum_v,2)+' %</b></div>';
                                lines += '</td>';
                                lines += '</tr>';
                            });
                        }
                        else {
                            que = item.question;
                            lines += '<tr>';
                            lines += '<th colspan="2">';
                            // lines += '<div class="line-title" style="font-weight: 700;">'+item.question+'</div><div class="line-body">'+item.answer+' - '+item.votes+'</div>';
                            lines += '<div class="line-title" style="font-weight: 700;">'+item.question+'</div>';
                            lines += '</th >';
                            item.data.map(function(item_data){
                                // console.log(non_json);
                                lines += '<tr>';
                                lines += '<td>';
                                lines += '<div style="width:460px;">'+item_data.answer;
                                lines += '<div class="votes" style="width:'+(item_data.votes/item.max_v*460);
                                lines += 'px;">'+item_data.votes+'</div></div>';
                                lines += '</td>';
                                lines += '<td>';
                                lines += '<div style="padding: 8px 0 0 20px;"><b>';
                                if (item.sum_v==0) lines += '0 %</div>';
                                else lines += Math.round(100*item_data.votes/item.sum_v,2)+' %</b></div>';
                                lines += '</td>';
                                lines += '</tr>';
                            });
                            
                        }
                    });
                    $('#poll_results').find('.modal-title').text(name_poll);
                    $('#poll_results').find('.table-res-poll tbody').html(lines);
                    $('#poll_results').modal('show');
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

    $('#btnPrint').on('click', function () {
        var printContents = new $("#poll_results .modal-body").clone();           
        var myWindow = window.open("", "popup", "width=600,height=600,scrollbars=yes,resizable=yes," +
            "toolbar=no,directories=no,location=no,menubar=no,status=no,left=0,top=0");
        var doc = myWindow.document;
        doc.open();
        $(printContents).find("#poll_results .modal-body").remove();
        doc.write("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">");
        doc.write("<html>");
        doc.write("<head>"); 
        doc.write("</head>");
        doc.write("<body>");
        doc.write("<style>");
        doc.write(".votes {background-color: #CCCCFF;text-align: center;border: 1px solid #0033FF;margin: 0 0 10px 0;}.line-title {float: left;}");
        doc.write("</style>");
        doc.write($(printContents).html());
        doc.write("<script>");
        doc.write("window.print();");
        doc.write("</script>");
        doc.write("</body>");
        doc.write("</html>");
        doc.document.close();
        doc.focus();
        doc.close();
    });

});