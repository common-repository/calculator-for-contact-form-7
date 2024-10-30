<?php
/**
* Plugin Name: Calculator For Contact Form 7
* Description: You can be calculate field between each other fields.
* Version: 1.0
* Copyright: 2022
* Text Domain: calculator-for-contact-form-7
*/


include("admin/admin.php");

add_action("wp_enqueue_scripts","cfc_wp_enqueue_scriptsfront",1000);
function cfc_wp_enqueue_scriptsfront(){
        wp_enqueue_script("cfc_cf7_calculator",plugin_dir_url( __FILE__ )."/js/cf7_calculator_field.js");

}