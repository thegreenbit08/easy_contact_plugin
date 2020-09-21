(function($) {

    const css_prefix = 'easy_contact';

    let ajax_url = messages_cms_ajax_object.ajaxurl;

    let active_id;
    let select_all;

    let first_load = true;

    let contact_message_table;

    let page_link;
    let contact_messages_table;
    let contact_messages_table_row;
    let contact_messages_table_row_first_child;
    let contact_messages_table_cell;
    let contact_messages_table_cell_first_row;
    let contact_messages_table_cb_input;

    let delete_message_btn;
    let select_all_messages_btn;
    let reply_contact_message_btn;

    let input_field_id;
    let input_field_name;
    let input_field_phone;
    let input_field_email;
    let input_field_reciption_date;
    let input_field_response_date;
    let input_field_message;

    let unread_messages_badge;

    let form_control;

    $(document).ready(function() {

        global_variable_declaration();

        contact_message_table = $(contact_messages_table).DataTable({
            "order": [ 4, 'des' ],
            "drawCallback": function( settings ) {
                if (!first_load) {
                    click_focus_key_events();
                    get_message_details_on_load();
                    check_message_is_read();
                    count_unread_messages();
                }
            }
        });

        first_load = false;

        select_all = false;

        click_focus_key_events();
        get_message_details_on_load();
        check_message_is_read();
        count_unread_messages();
    });

    function global_variable_declaration() {

        page_link = '.page-link';
        contact_messages_table = '#' + css_prefix +'_messages_table';
        contact_messages_table_row = '#' + css_prefix +'_messages_table tbody tr';
        contact_messages_table_row_first_child = '#' + css_prefix +'_messages_table tbody tr:first-child';
        contact_messages_table_cell = '#' + css_prefix +'_messages_table tbody tr td';
        contact_messages_table_cell_first_row = '#' + css_prefix +'_messages_table tr:first-child td';
        contact_messages_table_cb_input = '#' + css_prefix +'_messages_table tbody tr td:first-child input'

        delete_message_btn = '#' + css_prefix +'_delete_message_btn';
        select_all_messages_btn = '#' + css_prefix +'_select_all_btn';
        reply_contact_form_message_btn = '#' + css_prefix +'_reply_contact_form_message_btn';

        input_field_id = '#' + css_prefix +'_message_details_id';
        input_field_name = '#' + css_prefix +'_message_details_name';
        input_field_phone = '#' + css_prefix +'_message_details_phone';
        input_field_email = '#' + css_prefix +'_message_details_email';
        input_field_reciption_date = '#' + css_prefix +'_message_details_date';
        input_field_response_date = '#' + css_prefix +'_message_details_response_date';
        input_field_message = '#' + css_prefix +'_message_details_message';

        unread_messages_badge = '#' + css_prefix +'_unread_messages_counter';

        form_control = '.form-control';
    }

    function click_focus_key_events() {

        $(contact_messages_table_cell).on("click", function() {
            get_message_details_on_row_click(this);
        });

        $(delete_message_btn).on("click", function(e) {

            e.preventDefault();
            $(this).blur(); 
            e.stopImmediatePropagation();

            if(confirm("Are you sure you want to delete message?")){
                delete_message();
            }
            else {
                return false;
            }  
        });

        $(select_all_messages_btn).on("click", function(e) {

            e.preventDefault();
            $(this).blur();

            select_all_messages();
        });

        $(reply_contact_form_message_btn).on('focus', function(e) {

            e.preventDefault();
            $(this).blur();
        });

        $(form_control).on("keydown", function() {
            contact_messages_table_row.removeClass('' + css_prefix +'_table_selected_row');
            contact_messages_table_row.addClass('' + css_prefix +'_table_row_background_color_white');
        });

        $(contact_messages_table_cb_input).on("click", function() {
            select_all = false;
        });
    }

    function get_message_details_on_load() {

        let data = 'action=get_' +css_prefix + '_message_details';

        $.ajax({
            url: ajax_url,
            type: 'post',
            data: data,
            dataType: 'json',
            success: function(response) {

                if (response != null) {
                    active_id = response.id;
                    $(contact_messages_table_row_first_child).addClass(css_prefix +'_table_selected_row');
                } else {
                    active_id = -1;
                }

                update_message_details(response);
            },
            error: function(response) {
                console.error(response);
            }
        });
    }

    function get_message_details_on_row_click(selected_table_cell) {

            let id = $(selected_table_cell).parent().data("id");
            let data = 'action=get_' + css_prefix +'_message_details&id='+id;

            $.ajax({
                url: ajax_url,
                type: 'post',
                data: data,
                dataType: 'json',
                success: function(response) {
                    
                    update_message_details(response);
                    active_id = response.id;

                    set_is_read(id);
                },
                error: function(response) {
                    console.error(response);
                }
            });

            $(contact_messages_table_row).removeClass(css_prefix + '_table_selected_row');
            $(contact_messages_table_row).addClass(css_prefix + '_table_row_background_color_white');
            $(selected_table_cell).parent().removeClass(css_prefix + '_table_row_background_color_white');
            $(selected_table_cell).parent().addClass(css_prefix + '_table_selected_row');

            $(selected_table_cell).parent().find("td").removeClass(css_prefix + "_message_is_new");
    }

    function set_is_read(id) {
            let data = 'action=set_is_read&id='+id;

            $.ajax({
                url: ajax_url,
                type: 'post',
                data: data,
                dataType: 'json',
                success: function(response) {
                    console.log(response)
                    $(contact_messages_table).find("tr[data-id='" + id +"']").attr("data-is_read","1");
                    check_message_is_read();
                    count_unread_messages();
                },
                error: function(response) {
                    console.error(response);
                }
            });
    }

    function update_message_details(message) {

        let rows_qty = contact_message_table.row().count();

        if (rows_qty > 0) {
            set_table_button_activation(1);
            update_message_input_fields(message);        
        } else {
            set_table_button_activation(0);
            clear_message_input_fields();
        }

        if ($(input_field_email).val().length == 0) {
            $(reply_contact_form_message_btn).attr('disabled', true);
        } else {
            $(reply_contact_form_message_btn).attr('disabled', false);
        }
    }

    function update_message_input_fields(message) {

        let response_date = '-';

        if (message != null) {
            if (message.response_date != '0000-00-00 00:00:00.0' && message.response_date !== null) {
                response_date = message.response_date.slice(0, -2);
            }
        }

        $(input_field_id).attr('data-id', message.id);
        $(input_field_name).val(message.name);
        $(input_field_phone).val(message.phone);
        $(input_field_email).val(message.email);
        $(input_field_reciption_date).val(message.reciption_date);
        $(input_field_response_date).val(response_date);
        $(input_field_message).html(message.message);
    }

    function clear_message_input_fields() {
        $(input_field_name).val('');
        $(input_field_phone).val('');
        $(input_field_email).val('');
        $(input_field_reciption_date).val('');
        $(input_field_response_date).val('');
        $(input_field_message).html('');
    }

    function delete_message() {

        let selected_ids = get_selected_messages_id();
        let data = 'action=delete_' + css_prefix + '_message&ids='+selected_ids;

        $.ajax({
            url: ajax_url,
            type: 'post',
            data: data,
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if  (response.success == 1) {

                    let selected_ids_array = selected_ids.split(',');

                    $.each(selected_ids_array, function(index, id) {
                        selected_row = $(contact_messages_table).find("[data-id='" + id + "']");

                        contact_message_table.row(selected_row).remove();
                    });

                    contact_message_table.draw();

                    if (contact_message_table.row().count() == 0) {
                        clear_message_input_fields();

                        set_table_button_activation(0);
                    } else {
                        get_message_details_on_row_click($(contact_messages_table_cell_first_row));
                    }

                    $.notify(response.message, {type:"success", verticalAlign:"middle"});
                } else {
                    $.notify(response.message, {type:"warning", verticalAlign:"middle"});
                }  
            },
            error: function(response) {
                console.error(response);
            }
        });
    }

    function get_selected_messages_id() {

        let ids = '';

        $(contact_messages_table_row).each(function() {

            checked = $(this).find("td:first input:checked").prop("checked");

            if (checked) {
                ids += $(this).data("id") + ",";
            }
        });

        ids = ids.slice(0, -1);

        return ids;
    }

    function select_all_messages() {

        if (select_all == false) {
            $(contact_messages_table_row).each(function() {
                $(this).find("td:first input").prop('checked', true);
                select_all = true;
            });
        } else {
            $(contact_messages_table_row).each(function() {
                $(this).find("td:first input").prop('checked', false);
                select_all = false;
            });
        }
    }

    function check_message_is_read() {

        $(contact_messages_table_row).each(function() {

            if ($(this).attr("data-is_read") == 0) {
                $(this).find("td:not(:first-child)").addClass(css_prefix + "_message_is_new");
            } else {
                $(this).find("td:not(:first-child)").removeClass(css_prefix + "_message_is_new");
            }
        });    
    }

    function count_unread_messages() {

        let data = 'action=count_unread_messages';

        $.ajax({
            url: ajax_url,
            type: 'post',
            data: data,
            success: function(response) {
                $(unread_messages_badge).html(response);
            },
            error: function(response) {
                console.error(response);
            }
        });
    }

    function set_table_button_activation(rows_qty) {

        if (rows_qty > 0) {
            $(delete_message_btn).attr('disabled', false);
            $(select_all_messages_btn).attr('disabled', false);
            $(reply_contact_form_message_btn).attr('disabled', false);          
        } else {
            $(delete_message_btn).attr('disabled', true);
            $(select_all_messages_btn).attr('disabled', true);
            $(reply_contact_form_message_btn).attr('disabled', true);
        }
    }
})(jQuery);


