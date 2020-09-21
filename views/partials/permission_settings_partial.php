<form id="<?php echo XMN5_CSS_PREFIX; ?>_permission_settings_form">
    <?php if ($_SESSION["user_role_id"] == 1) { ?>
        <div id="<?php echo XMN5_CSS_PREFIX; ?>_permission_setting_first_row" class="row <?php echo XMN5_CSS_PREFIX; ?>_permission_setting_row">
            <div class="col-md-3 col-sm-4"><label for="<?php echo XMN5_CSS_PREFIX; ?>_read_messages_permission">Read Messages</label></div>
            <div class="col-dm-9 col-sm-8">
                <select id="<?php echo XMN5_CSS_PREFIX; ?>_read_messages_permission" class="<?php echo XMN5_CSS_PREFIX; ?>_permission_setting" name="read_messages_permission">
                    <option value="1" <?php ($this->_permission_settings['read_messages_permission'] == '1') ? print 'selected' : print '' ?>>Administrator</option>
                    <option value="2" <?php ($this->_permission_settings['read_messages_permission'] == '2') ?  print 'selected' : print '' ?>>Editor</option>
                </select></div>
        </div>
         <div class="row <?php echo XMN5_CSS_PREFIX; ?>_permission_setting_row">
            <div class="col-md-3 col-sm-4"><label for="<?php echo XMN5_CSS_PREFIX; ?>_reply_messages_permission">Reply Messages</label></div>
            <div class="col-md-9 col-sm-8">
                <select id="<?php echo XMN5_CSS_PREFIX; ?>_reply_messages_permission" class="<?php echo XMN5_CSS_PREFIX; ?>_permission_setting" name="reply_messages_permission">
                    <option value="1" <?php ($this->_permission_settings['reply_messages_permission'] == '1') ? print 'selected' : print '' ?>>Administrator</option>
                    <option value="2" <?php ($this->_permission_settings['reply_messages_permission'] == '2') ? print 'selected' : print '' ?>>Editor</option>
                </select>
            </div>
        </div>
        <div class="row <?php echo XMN5_CSS_PREFIX; ?>_permission_setting_row">
            <div class="col-md-3 col-sm-4"><label for="<?php echo XMN5_CSS_PREFIX; ?>_delete_messages_permission">Delete Messages</label></div>
            <div class="col-md-9 col-sm-8">
                <select id="<?php echo XMN5_CSS_PREFIX; ?>_delete_messages_permission" class="<?php echo XMN5_CSS_PREFIX; ?>_permission_setting" name="delete_messages_permission">
                    <option value="1" <?php ($this->_permission_settings['delete_messages_permission'] == '1') ? print 'selected' : print '' ?>>Administrator</option>
                    <option value="2" <?php ($this->_permission_settings['delete_messages_permission'] == '2') ? print 'selected' : print '' ?>>Editor</option>
                </select>
            </div>
        </div>
        <div class="row <?php echo XMN5_CSS_PREFIX; ?>_permission_setting_row">
            <div class="col-md-3 col-sm-4"><label for="<?php echo XMN5_CSS_PREFIX; ?>_edit_settings_permission">Edit Settings</label></div>
            <div class="col-md-9 col-sm-8">
                <select id="<?php echo XMN5_CSS_PREFIX; ?>_edit_settings_permission" class="<?php echo XMN5_CSS_PREFIX; ?>_permission_setting" name="edit_settings_permission">
                    <option value="1" <?php ($this->_permission_settings['edit_settings_permission'] == '1') ? print 'selected' : print '' ?>>Administrator</option>
                    <option value="2" <?php ($this->_permission_settings['edit_settings_permission'] == '2') ? print 'selected' : print '' ?>>Editor</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12"><input type="button" id="<?php echo XMN5_CSS_PREFIX; ?>_set_permission_settings_btn" class="button button-primary" value="Update Settings"/></div>
        </div>
    <?php } ?>
</form>
