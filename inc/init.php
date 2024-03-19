<?php
/**
 * Init includes folder and files
 *
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// include tgmpa requirement
// Use the filter 'swp_enable_TGMPA' to disable inclusion of TGMPA in your theme's functions.php.
// add_filter('swp_enable_TGMPA', '__return_false');
if (apply_filters('swp_enable_TGMPA', true)) {
    require_once dirname(__FILE__) . '/required_plugins.php';
}

//include core-wp files
require_once dirname( __FILE__ ) . '/core-wp/core_wp.php';
