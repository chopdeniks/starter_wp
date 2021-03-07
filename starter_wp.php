<?php 
/**
 * Plugin Name:       Starter Plugin
 * Plugin URI:        https://github.com/chopdeniks/starter_wp
 * Description:       This is starter plugin with minimal tasks automated by just installing. No setting required. 
 * Version:           1.0.0
 * Author:            Nikhil Chopde
 * Text Domain:       github-updater
 * GitHub Plugin URI: https://github.com/chopdeniks/starter_wp
 */

/*
 * Exit if called directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

remove_action('welcome_panel', 'wp_welcome_panel');
