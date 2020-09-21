(function($) {

    const css_prefix = 'easy_contact';

    let easy_contact_form;
    let easy_contact_form_submit_btn;

    let ajax_url = easy_contact_frontend_form_script_ajax_object.ajaxurl;

    $(document).ready(function() {

        easy_contact_form = '#' + css_prefix + '_form';
        easy_contact_form_submit_btn = '#' + css_prefix + '_form_submit_btn';

        $(easy_contact_form).validate();

        $(easy_contact_form_submit_btn).on('click',function(e) {
            e.preventDefault();

            $(this).blur();

            if ($(easy_contact_form).valid()){
                send_contact_form_message();
            }
        });
    });

    function send_contact_form_message() {
        let data = $(easy_contact_form).serialize();
        data += "&action=" + css_prefix + "_send_form";

        $.ajax({

            url: ajax_url,
            type: 'post',
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success == 1) {
                    $.notify(response.message, {type:"success", verticalAlign:"middle"});
                } else {
                    $.notify(response.message, {type:"warning", verticalAlign:"middle"});
                }
            },
            error: function() {
                
            }
        });
    }
})(jQuery);
