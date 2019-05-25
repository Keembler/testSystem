$(document).ready(function() { // зaпускaем скрипт пoсле зaгрузки всех элементoв

        $("#search").keyup(function() {
            var value = this.value;
            $("table.list_users").find("tr").each(function(index) {
                if (index === 0) return;
                var id = $(this).find("td").first().next().text();
                $(this).toggle(id.indexOf(value) !== -1);
            });
        });
    
    /**
     * Функция для Добавления нового пользователя
     */
    $(document).on('click', '.btn-add-user', function(e){
        e.preventDefault();
        
        var $form = $(e.target).parent('form'),
            formData = $form.serialize(), $url;

        if ($form.find('#edited').val() !== '') {
            $url = '/save_user';
            console.log("Сохранаяем новую информацию");
        }
        else {
            $url = '/add_user';
            console.log("Добавляем нового пользователя");
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
                        var user = rsp.user;
                        var count = $('.list_users tbody tr').length+1;
                        var newTR = `<tr>
                                        <td>${count}</td>
                                        <td>${user.login}</td>
                                        <td>${user.role}</td>
                                        <td>
                                            <button class='glyphicon glyphicon-pencil edit-user' aria-hidden='true' data-id='${user.id}'></button>
                                        </td>
                                        <td>
                                            <span class='glyphicon glyphicon-remove remove-user' aria-hidden='true' data-id='${user.id}'></span>
                                        </td>
                                    </tr>`;
                        $('.list_users').append(newTR);
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
     * Функция для удаления пользователя
     */
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

    /**
     * Функция получения информации о пользователе для редактирования
     */
    $(document).on('click', '.glyphicon-pencil', function(e){
        e.preventDefault();
        console.log("Редактирование пользователя");
        var $id = parseInt($(e.target).attr('data-id'));

        $.ajax({
            url: '/ed_user',
            type: 'post',
            data: 'id='+$id,
            success: function(resp) {
                console.log("Пришёл ответ", resp);
                var rsp = JSON.parse(resp);
                if (rsp.status === 200) {
                    var $user = rsp.user;
                    $('#add_user').find('#id_user').val($user.id);
                    $('#add_user').find('#edited').val(1);
                    $('#add_user').find('#fio').val($user.fio);
                    $('#add_user').find('#login').val($user.login);
                    $('#add_user').find('#role').val($user.role);
                    $('#add_user').modal('show');
                }
            }
        });

    });

    /**
     * Функция для очистки полей после закрытия модального окна
     */
    $('#add_user').on('hidden.bs.modal', function (e) {
        $('#add_user').find('input[type="text"],input[type="password"],input[type="hidden"]').val("");
        $('#add_user').find('input[type="checkbox"]').prop('checked',false);
        $('.status-text').removeClass('text-success').removeClass('text-danger').hide();
    })

});