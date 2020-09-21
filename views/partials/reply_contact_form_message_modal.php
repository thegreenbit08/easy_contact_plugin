<div class="modal fade"  id="<?php echo XMN5_CSS_PREFIX; ?>_reply_contact_form_message_modal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Reply Message</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body container">
        <table id="<?php echo XMN5_CSS_PREFIX; ?>_reply_contact_form_message_mail_header_details_tbl">
        <tr><td>Sender: </td><td  id="<?php echo XMN5_CSS_PREFIX; ?>_sender_email_adr"><?php echo get_option(XMN5_CSS_PREFIX.'_sender_mail_adr'); ?></td></tr>
        <tr><td>Receiver: </td><td id="<?php echo XMN5_CSS_PREFIX; ?>_receiver_email_adr"></td></tr>
        <tr><td>Send me a copy </td><td><input id="<?php echo XMN5_CSS_PREFIX; ?>_send_copy_cb" type="checkbox"/></td></tr>
        </table>
        <input id="<?php echo XMN5_CSS_PREFIX; ?>_reply_contact_form_message_modal_subject_input" name="reply_contact_form_message_modal_subject_input" type="text" placeholder="Subject" maxlength="250" required/>
        <br/>
        <textarea id="<?php echo XMN5_CSS_PREFIX; ?>_reply_contact_form_message_modal_message_input" name="reply_contact_form_message_modal_message_input" type="text" placeholder="Please enter your message" required></textarea>    
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button id="<?php echo XMN5_CSS_PREFIX; ?>_reply_contact_form_message_modal_reply_btn" type="button" class="button btn-sm button-primary <?php echo XMN5_CSS_PREFIX; ?>_reply_contact_form_message_modal_btn">Reply</button>
        <button type="button" class="button btn-sm button-danger <?php echo XMN5_CSS_PREFIX; ?>_reply_contact_form_message_modal_btn" data-dismiss="modal">Cancel</button>
      </div>

    </div>
  </div>
</div>
