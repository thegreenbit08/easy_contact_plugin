<?php

require_once plugin_dir_path(__FILE__).'../interfaces/statistic_interface.php';
require_once plugin_dir_path(__FILE__).'../db_communicator.php';

class Statistic implements Statistic_Interface {

    private $_statistic;

    function __construct() {

    }

    /*  Creates Statistic Page
    *   Input: none
    *   Return: none (page via require)
    */  
    public function render_page() {

        $this->_statistic = $this->_get_statistic();
        ?>

        <div id="<?php echo XMN5_CSS_PREFIX; ?>_statistic_container" class="container-fluid">
            <div id="<?php echo XMN5_CSS_PREFIX; ?>_info_card" class="card">

                <div id="<?php echo XMN5_CSS_PREFIX; ?>_statistic_card_header" class="card-header">
                    <h5>Mail Statistic</h5>
                </div>

                <div class="card-body">
                    
                <?php require 'partials/statistic_details_partial.php'; ?>

                </div>
            </div>
        </div>
        <?php
    }

    /*  Clears statistic
    *   Input: none
    *   Return: string confirmation message
    */  
    public function clear_statistic() {;

        $db_communicator = new DB_Communicator();
        echo json_encode($db_communicator->clear_table(XMN5_STATISTIC_DB_TABLE));
    
        die();
    }

    /*  Gets statistic
    *   Input: none
    *   Return: object $counter
    */  
    private function _get_statistic() {
    
        $db_communicator = new DB_Communicator();

        $statistic_data = $db_communicator->get_all(XMN5_STATISTIC_DB_TABLE);

        $counter = new stdClass();

        $counter->today = 0;
        $counter->yesterday = 0;
        $counter->week = 0;
        $counter->month = 0;
        $counter->year = 0;
        $counter->total = 0;

        $today = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"),date("Y")));
        $yesterday = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")-1,date("Y")));
        $week = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")-7,date("Y")));
        $month = date("Y-m-d",mktime(0, 0, 0, date("m")-1, date("d"),date("Y")));
        $year = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"),date("Y")-1));   

        foreach($statistic_data as $value) {

            $date = new DateTime($value["date"]);
            $date = $date->format("Y-m-d");

            $counter->total++;

            if ($date == $today) {
                $counter->today++;
            }

            if ($date == $yesterday) {
                $counter->yesterday++;
            }

            if (strtotime($date) >= strtotime($week)) {
                $counter->week++;
            }

            if (strtotime($date) >= strtotime($month)) {
                $counter->month++;
            }

            if (strtotime($date) >= strtotime($year)) {
                $counter->year++;
            } 
        }

        return $counter;
    }
}

add_action('wp_ajax_clear_statistic', array('Statistic','clear_statistic'));




