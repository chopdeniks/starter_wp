<?php 
/**
 * Plugin Name:       Starter Plugin
 * Plugin URI:        https://github.com/chopdeniks/starter_wp
 * Description:       This is starter plugin with minimal tasks automated by just installing. No setting required. 
 * Version:           1.0.0
 * Author:            Nikhil Chopde
 * Text Domain:       starter-plugin
 * GitHub Plugin URI: https://github.com/chopdeniks/starter_wp
 */

/*
 * Exit if called directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
register_activation_hook( __FILE__, 'starter_wp_activation' );
function starter_wp_activation() {
	add_option( 'starter_wp_activated', time() );
	
    // Find and delete the WP default 'Sample Page'
    $defaultPage = get_page_by_title( 'Sample Page' );
    wp_delete_post( $defaultPage->ID, $bypass_trash = true );

    // Find and delete the WP default 'Hello world!' post
    $defaultPost = get_posts( array( 'title' => 'Hello World!' ) );
    wp_delete_post( $defaultPost[0]->ID, $bypass_trash = true );	
}

add_action('admin_init', function(){
    
});

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

add_action('admin_menu', function(){
    remove_filter( 'update_footer', 'core_update_footer' );
});

add_filter('admin_footer_text', 'starter_wp_admin_footer_change');
function starter_wp_admin_footer_change () {
  echo '<i>Thank you!</i>';
}

add_filter('admin_head', function(){
    $screen = get_current_screen();
    $screen->remove_help_tabs();    
});

add_action('wp_before_admin_bar_render', function(){
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu( 'wp-logo' );    
});


// if js_composer active
if ( in_array( 'js_composer/js_composer.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    add_action('vc_after_init', function(){
        vc_disable_frontend();
    });
    
    add_action('admin_init', function(){
            setcookie('vchideactivationmsg', '1', strtotime('+3 years'), '/');
            setcookie('vchideactivationmsg_vc11', (defined('WPB_VC_VERSION') ? WPB_VC_VERSION : '1'), strtotime('+3 years'), '/');
    });    
}
