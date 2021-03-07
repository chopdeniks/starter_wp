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

add_filter('show_admin_bar', '__return_false');

add_action( 'wp_dashboard_setup', 'starter_wp_remove_all_dashboard_metaboxes' );
function starter_wp_remove_all_dashboard_metaboxes() {
    // Remove Welcome panel
    remove_action( 'welcome_panel', 'wp_welcome_panel' );
    // Remove the rest of the dashboard widgets
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
    remove_meta_box( 'health_check_status', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');
	remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal');
}

add_action('admin_head', 'starter_wp_admin_styles');
function starter_wp_admin_styles() {
?>
<style>
#adminmenu li.wp-not-current-submenu.wp-menu-separator {
    display: none!important;
}	
</style>
<?php	
}

add_action('admin_menu','starter_wp_admin_footer_version');
function starter_wp_admin_footer_version() {
    remove_filter( 'update_footer', 'core_update_footer' );
}

add_filter('admin_footer_text', 'starter_wp_admin_footer_change');
function starter_wp_admin_footer_change () {
  echo '<i>Thank you!</i>';
}

add_filter('contextual_help_list','starter_wp_contextual_help_remove');
function starter_wp_contextual_help_remove(){
    global $current_screen;
    $current_screen->remove_help_tabs();
}

add_action( 'wp_before_admin_bar_render', 'starter_wp_admin_bar_remove_logo', 0 );
function starter_wp_admin_bar_remove_logo() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu( 'wp-logo' );
}
