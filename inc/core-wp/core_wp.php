<?php
/**
 * Code to modify wordpress core functionalities 
 *
 * @since 1.0.0
 */


// Set default values for admin options
add_filter('pre_option_default_pingback_flag', '__return_zero');
add_filter('pre_option_default_ping_status', '__return_zero');
add_filter('pre_option_default_comment_status', '__return_zero');
add_filter('pre_option_moderation_notify', '__return_zero');


// Disable admin email verification
add_filter('admin_email_check_interval', '__return_false');