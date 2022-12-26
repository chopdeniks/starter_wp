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
register_deactivation_hook( __FILE__, 'starter_wp_deactivation' );
function starter_wp_activation() {
	add_option( 'starter_wp_activated', time() );
	do_action( 'remove_default_posts_pages' );	
	add_option( 'swp_settings', array() );
	swp_settings_default_options();
}

function starter_wp_deactivation() {
	delete_option( 'starter_wp_activated' );
	delete_option( 'swp_settings' );
}

$constant_name_prefix = 'SWP_';
defined( $constant_name_prefix . 'DIR' ) or define( $constant_name_prefix . 'DIR', dirname( plugin_basename( __FILE__ ) ) );	//SWP_DIR
defined( $constant_name_prefix . 'URL' ) or define( $constant_name_prefix . 'URL', plugin_dir_url( __FILE__ ) );	//SWP_URL
defined( $constant_name_prefix . 'PATH' ) or define( $constant_name_prefix . 'PATH', plugin_dir_path( __FILE__ ) );	//SWP_PATH

require_once( SWP_PATH . '/inc/required_plugins.php' );
require_once( SWP_PATH . '/inc/plugin_setting.php' );


add_action('remove_default_posts_pages', function(){
	// Find and delete the WP default 'Hello world!' post
	$defaultPost = get_posts( array( 'title' => 'Hello World!' ) );
	if ( $defaultPost[0]->ID == "1" ){
		wp_delete_post( $defaultPost[0]->ID, $bypass_trash = true );
	}
	// Find and delete the WP default 'Sample Page'
	$defaultPage = get_page_by_title( 'Sample Page' );
	if ( $defaultPage->ID == "2" ){
		wp_delete_post( $defaultPage->ID, $bypass_trash = true );
	}    
});

add_action('admin_init', function(){});

function swp_settings_default_options(){
    $swp_options = array(
        'disable_admin_bar'=> '0',

    );    
    update_option( 'swp_settings', $swp_options );
}

function swp_plist(){
	$plugins_list = array(
		'elementor'  		=> 'elementor/elementor.php',
		'woocommerce'		=> 'woocommerce/woocommerce.php',
		'wpforms-lite'		=> 'wpforms-lite/wpforms.php',
		'js_composer' 		=> 'js_composer/js_composer.php',
		'mailchimp-for-wp'	=> 'mailchimp-for-wp/mailchimp-for-wp.php',
		'updraftplus'	    => 'updraftplus/updraftplus.php',
		'gravityforms'	    => 'gravityforms/gravityforms.php',
	);
	return $plugins_list;
}
function is_swp_plugins_active( $path ){
	return in_array( $path, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ); 
}

add_filter( 'enable_post_by_email_configuration', '__return_false' );

add_filter( 'show_admin_bar', 'swp_hide_admin_bar' );
function swp_hide_admin_bar( $show ) {
    $swp_options = get_option( 'swp_settings' );
	if ( $swp_options ) {
		$disable_admin_bar = $swp_options['disable_admin_bar'];
		if ( $disable_admin_bar == 1 ) {
			return false;
		}
	}	
	return $show;
}

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
	// Remove YITH news widgets
	remove_meta_box( 'yith_dashboard_products_news', 'dashboard', 'normal');
	remove_meta_box( 'yith_dashboard_blog_news', 'dashboard', 'normal');
	// if Elementor active
	if ( is_swp_plugins_active( swp_plist()["elementor"] ) ) {	
		remove_meta_box( 'e-dashboard-overview', 'dashboard', 'normal');
		remove_meta_box('e-dashboard-widget-admin-top-bar', 'dashboard', 'normal');
	}	
	if ( is_swp_plugins_active( swp_plist()["wpforms-lite"] ) ) {
    	remove_meta_box( 'wpforms_reports_widget_lite', 'dashboard', 'normal' );
	} 
	if ( is_swp_plugins_active( swp_plist()["gravityforms"] ) ) {
    	remove_meta_box( 'rg_forms_dashboard', 'dashboard', 'normal' );
	}	
}

add_action('admin_head', 'starter_wp_admin_styles');
function starter_wp_admin_styles() {
?>
<style>	
#wc_actions, .wc_actions, .column-wc_actions {
    display: table-cell!important;
}	
</style>
<?php	
}

add_action('admin_menu', function(){
    remove_filter( 'update_footer', 'core_update_footer' );
	$separator = ['separator1','separator-woocommerce', 'separator2', 'separator-last'];
	foreach( $separator as $sep ) {
		remove_menu_page( $sep );
	}	
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
if ( is_swp_plugins_active( swp_plist()["js_composer"] ) ) {	

    add_action('vc_after_init', function(){
        vc_disable_frontend();
    });
    
    add_action('admin_init', function(){
            setcookie('vchideactivationmsg', '1', strtotime('+3 years'), '/');
            setcookie('vchideactivationmsg_vc11', (defined('WPB_VC_VERSION') ? WPB_VC_VERSION : '1'), strtotime('+3 years'), '/');
    });    
}

// if Mailchimp for WordPress active
if ( is_swp_plugins_active( swp_plist()["mailchimp-for-wp"] ) ) { 
    set_transient( 'mc4wp_api_key_notice_dismissed', 1, YEAR_IN_SECONDS );
}

// Automatically clear Autoptimize cache if it goes beyond 20MB
if (class_exists('autoptimizeCache')) {
    $myMaxSize = 20000; //20MB
    $statArr = autoptimizeCache::stats(); 
    $cacheSize = round($statArr[1]/1024);
    if ($cacheSize > $myMaxSize){
       autoptimizeCache::clearall();
       header("Refresh:0");
    }
    add_filter('autoptimize_filter_main_imgopt_plug_notice','__return_empty_string');
}


// if Updraftplus active
if ( is_swp_plugins_active( swp_plist()["updraftplus"] ) ) { 
    add_action('admin_init', function(){
        UpdraftPlus_Options::update_updraft_option('updraftplus_dismisseddashnotice', time() + 10*366*86400);
    });
}