<div class="modal fade"  id="<?php echo XMN5_CSS_PREFIX; ?>_auto_response_message_preview_modal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Auto Response Message Preview</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body container">
        <div class="row">
            <div id="<?php echo XMN5_CSS_PREFIX; ?>_auto_response_message_preview_modal_subject_col" class="col col-md-3">
                <label for="<?php echo XMN5_CSS_PREFIX; ?>_auto_response_message_preview_modal_subject">Subject</label>
            </div>
            <div class="col col-md-9">
                <p id="<?php echo XMN5_CSS_PREFIX; ?>_auto_response_message_preview_modal_subject"></p>
            </div>  
        </div>
        <hr>
        <div class="row">
            <div class="col col-md-12">
                <div id="<?php echo XMN5_CSS_PREFIX; ?>_auto_response_message_preview_modal_message" class="disable-scrollbars"></div>
            </div>
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button id="auto_response_message_preview_load_message_btn" type="button" class="button btn-sm button-primary <?php echo XMN5_CSS_PREFIX; ?>_auto_response_message_preview_modal_btn" data-id="" data-dismiss="modal">Load</button>
        <button type="button" class="button btn-sm button-danger <?php echo XMN5_CSS_PREFIX; ?>_auto_response_message_preview_modal_btn" data-dismiss="modal">Cancel</button>
      </div>

    </div>
  </div>
</div>
