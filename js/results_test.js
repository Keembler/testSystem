$(document).ready(function() { // зaпускaем скрипт пoсле зaгрузки всех элементoв

    var filter_select_el = document.getElementById('filter');
    var items_el = document.querySelector('.list_results tbody');

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

});