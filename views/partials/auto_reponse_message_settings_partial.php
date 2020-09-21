<?php require_once 'auto_response_message_preview_modal.php'; ?>

 <form id="<?php echo XMN5_CSS_PREFIX; ?>_auto_response_message_settings_form">
    <div class="row">
        <div class="col-md-3 col-sm-4"><label for="<?php echo XMN5_CSS_PREFIX; ?>_sender_name">Sender Name</label></div>
        <div class="col-md-9 col-sm-8"><input type="text" id="<?php echo XMN5_CSS_PREFIX; ?>_sender_name" name="sender_name" value="<?php echo $this->_mail_settings['sender_name'] ?>" maxlength="150" required/></div>
    </div>
    <div class="row">
        <div class="col-md-3 col-sm-4"><label for="<?php echo XMN5_CSS_PREFIX; ?>_sender_mail_adr">Sender Mail Address</label></div>
        <div class="col-md-9 col-sm-8"><input type="email" id="<?php echo XMN5_CSS_PREFIX; ?>_sender_mail_adr" name="sender_mail_adr" value="<?php echo $this->_mail_settings['sender_mail_adr'] ?>" maxlength="100" required/></div>
    </div>
    <div class="row">
        <div class="col-md-3 col-sm-4"><label for="<?php echo XMN5_CSS_PREFIX; ?>_auto_response_message_subject">Subject</label></div>
        <div class="col col-md-9 col-sm-8"><input type="text" id="<?php echo XMN5_CSS_PREFIX; ?>_auto_response_message_subject" name="auto_response_message_subject" value="<?php echo $this->_mail_settings['auto_response_message_subject'] ?>" maxlength="250" required/></div>
    </div>
    <div id="<?php echo XMN5_CSS_PREFIX; ?>_auto_response_message_row" class="row">
        <div class="col-md-3 col-sm-4"><label for="auto_response_message">Auto Response Message</label></div>
        <div class="col-md-9 col-sm-8">
            <textarea id="<?php echo XMN5_CSS_PREFIX; ?>_auto_response_message" name="auto_response_message"><?php echo $this->_mail_settings['auto_response_message']; ?></textarea>               
        </div>
    </div>
    <br/>
    <div id="<?php echo XMN5_CSS_PREFIX; ?>_send_auto_response_message_row" class="row">
        <div class="col-md-3 col-sm-4 col-10"><label for="<?php echo XMN5_CSS_PREFIX; ?>_send_auto_response_message">Send Auto Response Message</label></div>
        <div class="col-md-9 col-sm-8 col-2"><input type="checkbox" id="<?php echo XMN5_CSS_PREFIX; ?>_send_auto_response_message" name="send_auto_response_message" <?php echo $this->_mail_settings['send_auto_response_message']; ?>/></div>
    </div>
    
    <div class="row">
        <div class="col col-md-12">
            <input type="button" id="<?php echo XMN5_CSS_PREFIX; ?>_publish_auto_response_message_settings_btn" class="button button-primary" value="Publish"/>
            <hr id="<?php echo XMN5_CSS_PREFIX; ?>_auto_response_message_table_separator">
            <input type="button" id="<?php echo XMN5_CSS_PREFIX; ?>_save_auto_response_message_btn" class="button button-primary" value="Save New Message"/>                                        
            <input type="button" id="<?php echo XMN5_CSS_PREFIX; ?>_update_auto_response_message_btn" class="button button-primary" value="Update Message"/>                                         
            <input type="button" id="<?php echo XMN5_CSS_PREFIX; ?>_delete_auto_response_message_btn" class="button button-primary" value="Delete Messages"/>
            <input type="button" id="<?php echo XMN5_CSS_PREFIX; ?>_select_all_auto_response_message_btn" class="button button-primary" value="Select All Messages"/>
         </div>                                                                
    </div>
</form>

<table id="<?php echo XMN5_CSS_PREFIX; ?>_auto_response_message_table">
    <thead>
        <tr>
            <th></th>
            <th>Created</th>
            <th>Modified</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Controls</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($this->_auto_response_messages as $message) { 

        $message_exerp = substr($message["message"], 0, XMN5_AUTO_RESPONSE_MESSAGE_EXERP); ?>

        <tr data-id="<?php echo $message['id']; ?>">
            <td><input type="checkbox"/></td>
            <td><?php echo $message["creation_date"]; ?></td>
            <td><?php echo $message["last_modified_date"]; ?></td>
            <td><?php echo $message["subject"]; ?></td>
            <td><?php echo $message_exerp; ?></td>
            <td>
                <input type="button" class="btn btn-sm button-primary <?php echo XMN5_CSS_PREFIX; ?>_auto_response_message_preview_btn" value="Preview" data-toggle="modal" data-target="#<?php echo XMN5_CSS_PREFIX; ?>_auto_response_message_preview_modal"/>
                <input type="button" class="btn btn-sm button-primary <?php echo XMN5_CSS_PREFIX; ?>_load_auto_response_message_btn" value="Load" />
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
