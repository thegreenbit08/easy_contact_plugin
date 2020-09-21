<input id="<?php echo XMN5_CSS_PREFIX; ?>_message_details_id" type="text" data-id="" />
 
<div class="row">
    <div class="col-sm-3"><label for="<?php echo XMN5_CSS_PREFIX; ?>_message_details_name">Name</label></div>
    <div class="col-sm-9"><input type="text" id="<?php echo XMN5_CSS_PREFIX; ?>_message_details_name" readonly/></div>
</div>
<div class="row">
    <div class="col-sm-3"><label for="<?php echo XMN5_CSS_PREFIX; ?>_message_details_phone">Phone</label></div>
    <div class="col-sm-9"><input type="text" id="<?php echo XMN5_CSS_PREFIX; ?>_message_details_phone" readonly/></div>
</div>
<div class="row">
    <div class="col-sm-3"><label for="<?php echo XMN5_CSS_PREFIX; ?>_message_details_email">Email</label></div>
    <div class="col-sm-9"><input type="email" id="<?php echo XMN5_CSS_PREFIX; ?>_message_details_email" readonly/></div>
</div>
<div class="row">
    <div class="col-sm-3"><label for="<?php echo XMN5_CSS_PREFIX; ?>_message_details_date">Reciption Date</label></div>
    <div class="col-sm-9"><input type="text" id="<?php echo XMN5_CSS_PREFIX; ?>_message_details_date" readonly/></div>
</div>
<div class="row">
    <div class="col-sm-3"><label for="<?php echo XMN5_CSS_PREFIX; ?>_message_details_response_date">Response Date</label></div>
    <div class="col-sm-9"><input type ="text" id="<?php echo XMN5_CSS_PREFIX; ?>_message_details_response_date" readonly /></div>
</div>
<div class="row">
    <div class="col-sm-3"><label for="<?php echo XMN5_CSS_PREFIX; ?>_message_details_message">Message</label></div>
    <div class="col-sm-9"><textarea id="<?php echo XMN5_CSS_PREFIX; ?>_message_details_message" readonly></textarea></div>
</div>
<div class="row">
    <div class="col-sm-12">
        <?php if($_SESSION["user_role_id"] <= $_SESSION["delete_messages_permission_setting"]) { ?>
            <button id="<?php echo XMN5_CSS_PREFIX; ?>_delete_message_btn" class="button button-primary" disabled>Delete</button>
            <button id="<?php echo XMN5_CSS_PREFIX; ?>_select_all_btn" class="button button-primary" disabled>Select All</button>
        <?php } ?>
        <?php if($_SESSION["user_role_id"] <= $_SESSION["reply_messages_permission_setting"]) { ?>
            <button id="<?php echo XMN5_CSS_PREFIX; ?>_reply_contact_form_message_btn" class="button button-primary" data-toggle="modal" data-target="#<?php echo XMN5_CSS_PREFIX; ?>_reply_contact_form_message_modal" disabled>Reply</button>
        <?php } ?>
    </div>
</div>
