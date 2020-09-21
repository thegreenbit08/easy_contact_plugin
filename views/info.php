<?php

class Info {

    function __construct() {

    }

    /*  Creates Info Page
    *   Input: none
    *   Return: none (page via require)
    */  
    public function render_page() {

        ?>
        <div id="<?php echo XMN5_CSS_PREFIX; ?>_info_container" class="container-fluid">
            <div id="<?php echo XMN5_CSS_PREFIX; ?>_info_card" class="card">

                <div id="<?php echo XMN5_CSS_PREFIX; ?>_info_card_header" class="card-header">
                    <h5>Easy Contact Plugin</h5>
                </div>

                <div class="card-body">
                    <p>© Copyright 2020 by TheGreenBit</p>
                    <p>All rights reserved</p>
                    <br />
                    <p>If you have a question, recommendation or wish a customization we are looking forward to hearing from you.</p>
                    <p>Email: <a href="mailto:thegreenbit@mail.ee">thegreenbit@mail.ee</a></p>
                    <br>
                    <hr>

                    <br>
                    <h5>Shortcode: [easy_contact]</h5>
                </div>
            </div>
        </div>
        <?php
    }
}
