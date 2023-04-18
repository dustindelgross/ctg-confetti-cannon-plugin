<?php
/**
 * Plugin Name: CTG Confetti Cannon Plugin
 * Description: This is going to be a custom plugin that will allow us to easily update employee and location information.
 * Plugin URI:  https://celebrationtitlegroup.com/
 * Version:     1.0.0
 * Author:      Dustin Delgross
 * Author URI:  https://dustindelgross.com/
 * Text Domain: ctg
 * 
 */

defined( 'ABSPATH' ) ||	exit; // Exit if accessed directly.

$ctg_plugin_file = 'ctg-confetti-cannon/ctg-confetti-cannon-loader.php';

if ( ! defined( 'CTG_VERSION' ) ) {
	define( 'CTG_VERSION', '1.0.0' );
}

require dirname( __FILE__ ) . '/class-confetti-cannon.php';

function ctg() {
	return Confetti_Cannon::instance();
}

$_GLOBALS['ctg'] = ctg();