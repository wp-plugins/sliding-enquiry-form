<?php
/**
 * Sliding Enquiry Form main plugin file
 *
 * @package   Sliding Enquiry Form
 * @author    Jaimin Suthar <suthar.jems07@gmail.com>
 * @license   GPL-2.0+
 * @demo      http://smoexpert.in/
 *
 * @wordpress-plugin
 * Plugin Name: 	Sliding Enquiry Form
 * Description: 	Sliding Enquiry Form is a simple and easy wordpress plugin used to add popup on CSS3 animations. Show html code and shortcodes in popup.
 * Version: 1.0
 * Author: 			Jaimin Suthar
 * Text Domain: 	Sliding Enquiry Form
 * License: 		Open Source 
 */

// If this file is called directly, abort.

if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once plugin_dir_path( __FILE__ ) . 'class-sliding-enquiry-form.php';

add_action( 'plugins_loaded', array( 'Sliding_Enquiry', 'get_instance' ) );





