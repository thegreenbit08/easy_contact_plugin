(function($) {

    const css_prefix = 'easy_contact';

    let ajax_url = mail_settings_ajax_object.ajaxurl;

    const AUTO_RESPONSE_MESSAGE_EXERP_LENGTH = 60;

    let select_all;

    let first_load = true;

    let auto_response_message_datatable;
    let settings_navigation_tab_links;

    let auto_response_message_settings_form;
    let publish_auto_response_message_settins_btn;

    let set_permission_settings_btn;

    let save_auto_response_message_btn;
    let update_auto_response_message_btn;
    let delete_auto_response_message_btn;
    let select_all_auto_response_message_btn;

    let load_auto_response_message_table_btn;
    let preview_auto_response_message_table_btn;

    let auto_response_message_table;
    let auto_response_message_table_tbody;
    let auto_response_message_table_row;
    let auto_response_message_table_first_row;
    let auto_response_message_table_cb;
    let auto_response_message_table_page_link;

    let auto_response_message_preview_modal;
    let auto_response_message_preview_subject;
    let auto_response_message_preview_message;
    let auto_response_message_preview_message_btn;
    let auto_response_message_preview_load_message_btn;
    
    let auto_response_message_sender_name_input_field;
    let auto_response_message_sender_email_adr_input_field;
    let auto_response_message_subject_input_field;
    let auto_response_message_input_field;
    let send_auto_response_message_cb;

    let permission_settings_form;

    $(document).ready(function() {

        global_variable_declaration();

        auto_response_message_datatable = $(auto_response_message_table).DataTable({
            "order": [ 1, 'des' ],
            "drawCallback": function( settings ) {
                if (!first_load) {
                    set_active_table_row();

                    set_table_btn_click_focus_events();
                }
            }
        });

        set_table_button_activation();

        document_ready_click_focus_events();
        
        set_table_button_activation()
    });

    function global_variable_declaration() {

        select_all = false;

        first_load = false;

        set_pagination_on_load();

        settings_navigation_tab_links = '#settings_nav ul li a';

        auto_response_message_settings_form = '#' + css_prefix  + '_auto_response_message_settings_form';
        publish_auto_response_message_settings_btn = '#' + css_prefix  + '_publish_auto_response_message_settings_btn';
     
        set_permission_settings_btn = '#' + css_prefix  + '_set_permission_settings_btn';

        save_auto_response_message_btn = '#' + css_prefix  + '_save_auto_response_message_btn';
        update_auto_response_message_btn = '#' + css_prefix  + '_update_auto_response_message_btn';
        delete_auto_response_message_btn = '#' + css_prefix  + '_delete_auto_response_message_btn';
        select_all_auto_response_message_btn = '#' + css_prefix  + '_select_all_auto_response_message_btn';     

        load_auto_response_message_table_btn = '.' + css_prefix  + '_load_auto_response_message_btn';
        preview_auto_response_message_table_btn = '.' + css_prefix  + '_auto_response_message_preview_btn';

        auto_response_message_table = '#' + css_prefix  + '_auto_response_message_table';
        auto_response_message_table_tbody = '#' + css_prefix  + '_auto_response_message_table tbody';
        auto_response_message_table_row = '#' + css_prefix  + '_auto_response_message_table tbody tr';
        auto_response_message_table_first_row = '#' + css_prefix  + '_auto_response_message_table tr:first-child';
        auto_response_message_table_cb = '#' + css_prefix  + '_auto_response_message_table tr td:first-child input';
        auto_response_message_table_page_link = '#' + css_prefix  + '_auto_response_message_table_paginate .pagination .page-link';

        auto_response_message_preview_modal = '#' + css_prefix  + '_auto_response_message_preview_modal';
        auto_response_message_preview_subject = '#' + css_prefix  + '_auto_response_message_preview_modal_subject';
        auto_response_message_preview_message = '#' + css_prefix  + '_auto_response_message_preview_modal_message';
        auto_response_message_preview_message_btn = '.' + css_prefix  + '_auto_response_message_preview_btn';
        auto_response_message_preview_load_message_btn = '#auto_response_message_preview_load_message_btn';

        auto_response_message_sender_name_input_field = '#' + css_prefix  + '_sender_name';
        auto_response_message_sender_email_adr_input_field = '#' + css_prefix  + '_sender_mail_adr';
        auto_response_message_subject_input_field = '#' + css_prefix  + '_auto_response_message_subject';
        auto_response_message_input_field = '#' + css_prefix  + '_auto_response_message';
        send_auto_response_message_cb = '#' + css_prefix  + '_send_auto_response_message';

        permission_settings_form = '#' + css_prefix  + '_permission_settings_form';
    }

    function document_ready_click_focus_events() {
    
        $(settings_navigation_tab_links).on('click', function(e) {

            e.preventDefault();
            $(this).blur();
        });

        $(publish_auto_response_message_settings_btn).on('click',function(e) {

            e.preventDefault();

            $(this).blur();  

            // TODO jQuery Validator conflicts with Bootstrap

            if(confirm("Are you sure you want to update the Settings?")){
                if ($(auto_response_message_settings_form).valid()){
                    publish_auto_response_message_settings();
                } else {
                    $.notify('Please check your input', {type:"info", verticalAlign:"middle"});
                }
            }
            else {
                return false;
            }
        });

        $(set_permission_settings_btn).on('click',function(e) {

            e.preventDefault();
            $(this).blur();  

            // jQuery Validator conflicts with Bootstrap

            if(confirm("Are you sure you want to update the Settings?")){
                if ($(auto_response_message_settings_form).valid()){
                    update_permission_settings();
                } else {
                    $.notify('Please check your input', {type:"info", verticalAlign:"middle"});
                }
            }
            else {
                return false;
            }
        });

        $(save_auto_response_message_btn).on('click',function(e) {

            e.preventDefault();
            $(this).blur();

            save_auto_response_message();
        });

        $(update_auto_response_message_btn).on('click',function(e) {

            e.preventDefault();
            $(this).blur();

            if(confirm("Are you sure you want to update the message?")){
                update_auto_response_message(this);
            }
        });

        $(delete_auto_response_message_btn).on('click',function(e) {

            e.preventDefault();
            $(this).blur();

            if(confirm("Are you sure you want to delete the message(s)?")){
                delete_auto_response_message();
            }
        });

        $(select_all_auto_response_message_btn).on("click", function(e) {

            e.preventDefault();
            $(this).blur();

            select_all_messages();
        });

        $(auto_response_message_table_cb).on("click", function() {
            select_all = false;
        });

    }

    function set_table_btn_click_focus_events() {

         $(load_auto_response_message_table_btn).on('click', function(e) {

            e.preventDefault();
            e.stopImmediatePropagation();
            $(this).blur();

            let id = $(this).parents().eq(1).data('id');

            if(confirm("Current Settings will be overwritten. Do you wish to continue?")){

                
                load_auto_response_message(id);
            }
        });

        $(load_auto_response_message_table_btn).on('focus', function(e) {

            e.preventDefault();
            $(this).blur();
        });

        $(preview_auto_response_message_table_btn).on('click', function(e) {

            e.preventDefault();
            load_auto_response_message_preview(this);
        });

        $(preview_auto_response_message_table_btn).on('focus', function(e) {

            e.preventDefault();
            $(this).blur();
        });

        $(auto_response_message_preview_load_message_btn).on('click',function(e) {

            e.preventDefault();
            e.stopImmediatePropagation();

            let id = $(this).attr('data-id');

            if(confirm("Current Settings will be overwritten. Do you wish to continue?")){
                load_auto_response_message(id);
                $(auto_response_message_preview_modal).modal('hide');
            }
        }); 
    }

    function publish_auto_response_message_settings() {

        let sender_name = $(auto_response_message_sender_name_input_field).val();
        let sender_mail_adr = $(auto_response_message_sender_email_adr_input_field).val();
        let subject = $(auto_response_message_subject_input_field).val();
        let message = $(auto_response_message_input_field).val().replace(/\r\n|\r|\n/g,"<br />");
        let send_auto_response_message = $(send_auto_response_message_cb).prop("checked");

        let data = 'sender_name=' + sender_name + '&sender_mail_adr=' + sender_mail_adr + '&auto_response_message_subject=' + subject + 
        '&auto_response_message=' + message + '&send_auto_response_message=' + send_auto_response_message + '&action=' + css_prefix  + '_publish_auto_response_message_settings';   

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
            error: function(response) {
                console.error(response);
            }
        });
    }

    function update_permission_settings() {

        let form_data = $(permission_settings_form).serialize();
        let data = form_data + '&action=update_' + css_prefix  + '_permission_settings';   

        $.ajax({
            url: ajax_url,
            type: 'post',
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success == '1') {
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

    function load_auto_response_message_preview(this_btn) {

        let id = $(this_btn).parents().eq(1).data('id');

        let data = 'id=' + id + '&action=' + css_prefix  + '_load_auto_response_message_preview';

        $(auto_response_message_preview_subject).html('');
        $(auto_response_message_preview_message).html('');

        $.ajax({
            url: ajax_url,
            data: data,
            type: 'post',
            dataType: 'json',
            success: function(response) {
                
                $(auto_response_message_preview_subject).html(response.message.subject);
                $(auto_response_message_preview_message).html(response.message.message);

                $(auto_response_message_preview_load_message_btn).attr('data-id',id);    
            },
            error: function(response) {
                console.error(response);
            }
        });
    }

    function load_auto_response_message(id) {

        let data = 'id=' + id + '&action=' + css_prefix  + '_load_auto_response_message';

        $.ajax({
            url: ajax_url,
            data: data,
            type: 'post',
            dataType: 'json',
            success: function(response) {

                if (response.success == 1) {
                    $(auto_response_message_subject_input_field).val(response.message.subject);
                    $(auto_response_message_input_field).val(response.message.message);            

                    set_active_table_row();
                }
            },
            error: function(response) {
                console.error(response);
            }
        });
    }


    function save_auto_response_message() {

        let subject = $(auto_response_message_subject_input_field).val();
        let message = $(auto_response_message_input_field).val().replace(/\r\n|\r|\n/g,"<br />");
        let data = 'auto_response_message_subject=' + subject + '&auto_response_message=' + message + '&action=' + css_prefix  + '_save_auto_response_message';

        $.ajax({
            url: ajax_url,
            data: data,
            type: 'post',
            dataType: 'json',
            success: function(response) {

                if (response.success == '1') {
                    let message_exerp = response.message.message.substring(0,AUTO_RESPONSE_MESSAGE_EXERP_LENGTH);

                    auto_response_message_datatable.row.add([
                        '<input type="checkbox" />',
                        response.message.creation_date,
                        response.message.last_modified_date,
                        response.message.subject,
                        message_exerp,
                        '<input type="button" style="margin-right: 4px;" class="btn btn-sm button-primary ' + css_prefix  + '_auto_response_message_preview_btn" value="Preview" data-toggle="modal" data-target="#' +css_prefix  + '_auto_response_message_preview_modal"/>' +
                        '<input type="button" class="btn btn-sm button-primary ' + css_prefix  + '_load_auto_response_message_btn" value="Load" />'
                    ]).order([1, 'desc']).draw();

                    click_focus_events_after_save_message();

                    $(auto_response_message_table_first_row).attr('data-id', response.message.id);

                    set_active_table_row();

                    set_table_button_activation();

                    $.notify(response.message.response_message, {type:"success", verticalAlign:"middle"});
                } else {
                    $.notify(response.message, {type:"warning", verticalAlign:"middle"});
                }
            },
            error: function(response) {
                console.error(response);
            }
        });
    }

    function click_focus_events_after_save_message() {

        $(auto_response_message_preview_message_btn).on('click', function(e) {

            e.preventDefault();
            load_auto_response_message_preview(this);
        });

        $(auto_response_message_preview_message_btn).on('focus', function(e) {
            e.preventDefault();
            $(this).blur();
        });

        $(load_auto_response_message_table_btn).on('click', function(e) {

            e.preventDefault();
            e.stopImmediatePropagation();
            $(this).blur();

            let id = $(this).parents().eq(1).data('id');

            if(confirm("Current Settings will be overwritten. Do you wish to continue?")){   
                load_auto_response_message(id);
            }
        });

        $(load_auto_response_message_table_btn).on('focus', function(e) {

            e.preventDefault();
            $(this).blur();
        });   
    }

    function update_auto_response_message(this_btn) {

        let subject = $(auto_response_message_subject_input_field).val();
        let message = $(auto_response_message_input_field).val().replace(/\r\n|\r|\n/g,"<br />");
        let data = 'auto_response_message_subject=' + subject + '&auto_response_message=' + message + '&action=' + css_prefix  + '_update_auto_response_message';

        $.ajax({
            url: ajax_url,
            data: data,
            type: 'post',
            dataType: 'json',
            success: function(response) {

                if (response.success == '1') {
                    let selected_row = $(auto_response_message_table).find("[data-id='" + response.message.id + "']");

                    let message_exerp = message.substring(0,AUTO_RESPONSE_MESSAGE_EXERP_LENGTH);

                    selected_row.find("td:nth-child(3)").html(response.message.last_modified_date);
                    selected_row.find("td:nth-child(4)").html(subject);
                    selected_row.find("td:nth-child(5)").html(message_exerp);

                    $.notify(response.message.message, {type:"success", verticalAlign:"middle"});
                } else {
                    $.notify(response.message, {type:"warning", verticalAlign:"middle"});
                }
            },
            error: function(response) {
                console.error(response);
            }
        });
    }

    function delete_auto_response_message() {

        let selected_ids = get_selected_auto_response_messages_id();

        let data = 'action=' + css_prefix  + '_delete_auto_response_message&ids='+selected_ids;

        $.ajax({
            url: ajax_url,
            data: data,
            type: 'post',
            dataType: 'json',
            success: function(response) {

                console.log(response);

                if  (response.success == 1) {               
    
                    let selected_ids_array = selected_ids.split(',');

                    $.each(selected_ids_array, function(index, id) {
                        selected_row = $(auto_response_message_table).find("[data-id='" + id + "']");
                        auto_response_message_datatable.row(selected_row).remove();
                    });
                
                    auto_response_message_datatable.draw();

                    set_table_button_activation();
                

                    set_active_table_row();

                    $.notify(response.message, {type:"success", verticalAlign:"middle"});
                } else {
                    $.notify(response.message, {type:"warning", verticalAlign:"middle"});
                }
            }
        });
    }

    function get_selected_auto_response_messages_id() {

        let ids = '';

        $(auto_response_message_table_row).each(function(index, tr) {

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
            $(auto_response_message_table_row).each(function(index, tr) {
                $(this).find("td:first input").prop('checked', true);
                select_all = true;
            });
        } else {
            $(auto_response_message_table_row).each(function(index, tr) {
                $(this).find("td:first input").prop('checked', false);
                select_all = false;
            });
        }
    }

    function set_active_table_row() {

        let data = '&action=' + css_prefix  + '_get_current_auto_response_message_id';

        $.ajax({
            url: ajax_url,
            data: data,
            type: 'post',
            dataType: 'json',
            success: function(response) {
                $(auto_response_message_table_row).removeClass(css_prefix  + '_table_selected_row');
                $(auto_response_message_table_row).addClass(css_prefix  + '_table_row_background_color_white');

                if (response.id.length > 0) {
                    selected_row = $(auto_response_message_table_tbody).find("[data-id='" + response.id + "']");
                    $(selected_row).removeClass(css_prefix  + '_table_row_background_color_white');
                    $(selected_row).addClass(css_prefix  + '_table_selected_row');
                    $(update_auto_response_message_btn).attr('disabled', false);
                } else {
                    $(update_auto_response_message_btn).attr('disabled', true);
                }
            },
            error: function(response) {
                console.error(response);
            }
        });
    }

    function set_table_button_activation() {
        let row_count = auto_response_message_datatable.row().count();

        if (row_count > 0) {
            $(delete_auto_response_message_btn).attr('disabled',false);
            $(select_all_auto_response_message_btn).attr('disabled',false);
        } else {
            $(delete_auto_response_message_btn).attr('disabled',true);
            $(select_all_auto_response_message_btn).attr('disabled',true);
        } 
    }

    function set_pagination_on_load() {
    
        let rows_per_page = 10;

        let data = '&action=' + css_prefix  + '_get_current_auto_response_message_id';

        $.ajax({
            url: ajax_url,
            data: data,
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if(response.id.length > 0) {
                    let selected_row_index = auto_response_message_datatable.row("[data-id='" + response.id + "']").index();
                    let selected_page = Math.floor(selected_row_index/rows_per_page);

                    auto_response_message_datatable.page(selected_page).draw(false);
                } else {
                    auto_response_message_datatable.draw();
                }
            },
            error: function(response) {
                console.error(response);
            }
        });     
    }

    function set_table_button_activation() {

        if (auto_response_message_datatable.data().count() == 0) {
            $(update_auto_response_message_btn).attr('disabled',true);
            $(delete_auto_response_message_btn).attr('disabled',true);
            $(select_all_auto_response_message_btn).attr('disabled',true);
        } else {
            $(update_auto_response_message_btn).attr('disabled',false);
            $(delete_auto_response_message_btn).attr('disabled',false);
            $(select_all_auto_response_message_btn).attr('disabled',false);
        }
    }
})(jQuery);


