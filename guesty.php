<?php
/*
Plugin Name: Guesty Calendar Widgets
Plugin URI: 
Description: Display guesty calendar widgets
Author: Braudy Pedrosa
Version: 1.0
Author URI: http://buildupbookings.com/
*/

// avoid direct access
if ( !function_exists('add_filter') ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if(!defined('GUESTY_VERSION')){
	define('GUESTY_VERSION', "1.0"); 
}
if(!defined('GUESTY_DIR')){
	define('GUESTY_DIR', plugin_dir_path( __FILE__ )); 
}
if(!defined('GUESTY_URL')){
	define('GUESTY_URL', plugin_dir_url( __FILE__ )); 
}

include_once(GUESTY_DIR.'functions.php');
include_once(GUESTY_DIR.'shortcodes.php');

// Get all listings shortcode

function _guesty_calendar_enqueue_scripts(){
	wp_enqueue_script( 'gc-datepicker-script', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js', array('jquery'));
	wp_enqueue_style( 'gc-datepicker-style', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css' );
	wp_enqueue_script( 'gc-script', GUESTY_URL.'js/custom.js', array('jquery'), '1.0' );
	wp_enqueue_style( 'gc-style', GUESTY_URL.'css/style.css', '1.0' );
}

add_action('wp_enqueue_scripts', '_guesty_calendar_enqueue_scripts');


// defaults
// Secret : Ik-xsM3z5S_6blJp0ZR9rsxuwLowyyggm57mHHrL
// ID: 0oa8kk2aweSedCxLV5d7