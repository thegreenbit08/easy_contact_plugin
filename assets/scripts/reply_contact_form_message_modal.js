(function($) {
    
    const css_prefix = 'easy_contact';

    let ajax_url = reply_contact_form_message_ajax_object.ajaxurl;

    let reply_contact_form_message_modal;

    let reply_contact_form_message_parent_btn;

    let message_id_input;
    let receiver_mail_adr_parent_input;
    let receiver_email_adr_table_cell;
    let receiver_email_adr = '';

    let message_details_response_date;

    let reply_message_subject_input;
    let reply_message_input;

    let send_copy_cb;
    let send_copy = '';

    let reply_contact_form_message_modal_reply_btn;

    $(document).ready(function() {

        global_variable_declaration();

        document_ready_click_focus_events();
    });

    function global_variable_declaration() {

        reply_contact_form_message_modal = '#' + css_prefix +'_reply_contact_form_message_modal';

        reply_contact_form_message_parent_btn = '#' + css_prefix +'_reply_contact_form_message_btn';
        receiver_email_adr_parent_input = '#' + css_prefix +'_message_details_email';
        receiver_email_adr_table_cell = '#' + css_prefix +'_receiver_email_adr';

        message_details_response_date = '#' + css_prefix +'_message_details_response_date';

        message_id_input = '#' + css_prefix +'_message_details_id';
        reply_message_subject_input = '#' + css_prefix +'_reply_contact_form_message_modal_subject_input';
        reply_message_input = '#' + css_prefix +'_reply_contact_form_message_modal_message_input';

        send_copy_cb = '#' + css_prefix +'_send_copy_cb';

        reply_contact_form_message_modal_reply_btn = '#' + css_prefix +'_reply_contact_form_message_modal_reply_btn';
    }

    function document_ready_click_focus_events() {

        $(reply_contact_form_message_parent_btn).on('click', function(e) {

            e.preventDefault();

            receiver_email_adr = $(receiver_email_adr_parent_input).val();
            $(receiver_email_adr_table_cell).html(receiver_email_adr);
        });

        $(reply_contact_form_message_modal_reply_btn).on('click', function(e) {

            e.preventDefault();
        });

        $(reply_contact_form_message_modal_reply_btn).on('focus', function(e) {
        
            e.preventDefault();
            $(this).blur();

            reply_contact_form_message();

            $(reply_contact_form_message_modal).modal('hide');
        });
    }

    function reply_contact_form_message() {

        let id = $(message_id_input).attr('data-id');
        let mail_subject = $(reply_message_subject_input).val();
        let mail_message = $(reply_message_input).val().replace(/\r\n|\r|\n/g,"</br>");
        let send_copy = $(send_copy_cb).prop("checked");
        
        let data =  'id=' + id + '&receiver_email_adr=' + receiver_email_adr + '&mail_subject=' + mail_subject + 
                '&mail_message=' + mail_message + '&send_copy=' + send_copy + '&action=reply_contact_form_message';

        $.ajax({      
            url: ajax_url,
            data: data,
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response.success == 1) {

                    $(send_copy_cb).prop('checked', false);
                    $(reply_message_subject_input).val('');
                    $(reply_message_input).val('');

                    $(message_details_response_date).val(response.response_date);

                    $.notify(response.message, {type:"success", verticalAlign:"middle"});
                } else {
                    $.notify(response.message, {type:"warning", verticalAlign:"middle"});
                }
            }, error: function(response) {
                console.error(response);
            }
        });
    }
})(jQuery);






