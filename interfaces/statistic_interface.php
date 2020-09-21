<?php

interface Statistic_Interface {

    /*  Creates Statistic Page
    *   Input: none
    *   Return: none (page via require)
    */  
    public function render_page();

    /*  Clears statistic
    *   Input: none
    *   Return: string confirmation message
    */  
    public function clear_statistic();
}
