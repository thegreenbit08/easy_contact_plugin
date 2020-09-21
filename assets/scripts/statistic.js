(function($) {

    const css_prefix = 'easy_contact';

    let ajax_url = statistic_ajax_object.ajaxurl;

    let easy_contact_statistic_messages_today_value;
    let easy_contact_statistic_messages_yesterday_value;
    let easy_contact_statistic_messages_week_value;
    let easy_contact_statistic_messages_month_value;
    let easy_contact_statistic_messages_year_value;
    let easy_contact_statistic_messages_total_value;

    let easy_contact_clear_statistic_btn;

    $(document).ready(function() {

        global_variable_declaration();
        
        document_ready_click_events();
    });

    function global_variable_declaration() {

        easy_contact_statistic_messages_today_value = '#' + css_prefix +'_statistic_messages_today_value';
        easy_contact_statistic_messages_yesterday_value = '#' + css_prefix +'_statistic_messages_yesterday_value';
        easy_contact_statistic_messages_week_value = '#' + css_prefix +'_statistic_messages_week_value';
        easy_contact_statistic_messages_month_value = '#' + css_prefix +'_statistic_messages_month_value';
        easy_contact_statistic_messages_year_value = '#' + css_prefix +'_statistic_messages_year_value';
        easy_contact_statistic_messages_total_value = '#' + css_prefix +'_statistic_messages_total_value';

        easy_contact_clear_statistic_btn = '#' + css_prefix +'_clear_statistic_btn';
    }

    function document_ready_click_events() {

        $(easy_contact_clear_statistic_btn).on('click', function(e) {

            e.preventDefault();
            $(this).blur();

            if(confirm("Are you sure you want to clear Statistic?")){
                clear_statistic();
            }
            else {
                return false;
            }
        });
    }

    function clear_statistic() {
        let data = 'action=clear_statistic';

        $.ajax({

            url: ajax_url,
            type: 'post',
            data: data,
            dataType: 'json',
            success: function(response) {

                if (response.success == 1) {

                    $(easy_contact_statistic_messages_today_value).html('0');
                    $(easy_contact_statistic_messages_yesterday_value).html('0');
                    $(easy_contact_statistic_messages_week_value).html('0');
                    $(easy_contact_statistic_messages_month_value).html('0');
                    $(easy_contact_statistic_messages_year_value).html('0');
                    $(easy_contact_statistic_messages_total_value).html('0');       
                }

                $.notify(response.message, {type:"success", verticalAlign:"middle"});
            },
            error: function(response) {
                console.error(response);
            }
        });
    }

})(jQuery);
