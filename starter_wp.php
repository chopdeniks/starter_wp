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
	swp_delete_files_and_show_notice();
}

function starter_wp_deactivation() {
	delete_option( 'starter_wp_activated' );
}

$constant_name_prefix = 'SWP_';
defined( $constant_name_prefix . 'DIR' ) or define( $constant_name_prefix . 'DIR', dirname( plugin_basename( __FILE__ ) ) );	//SWP_DIR
defined( $constant_name_prefix . 'URL' ) or define( $constant_name_prefix . 'URL', plugin_dir_url( __FILE__ ) );	//SWP_URL
defined( $constant_name_prefix . 'PATH' ) or define( $constant_name_prefix . 'PATH', plugin_dir_path( __FILE__ ) );	//SWP_PATH

require_once( SWP_PATH . '/inc/init.php' );

// Hook the function to the 'init' hook, which is appropriate for plugin initialization
add_action( 'admin_init', 'remove_default_posts_pages' );
function remove_default_posts_pages() {
    // Check if 'Hello world!' post exists
    $hello_world_post = wpn_get_page_by_title( 'Hello World!', OBJECT, 'post' );
    if ( $hello_world_post ) {
        // Delete the 'Hello world!' post
        wp_delete_post( $hello_world_post->ID, true );
    }

    // Check if 'Sample Page' exists
    $sample_page = wpn_get_page_by_title( 'Sample Page', OBJECT, 'page' );
    if ( $sample_page ) {
        // Delete the 'Sample Page'
        wp_delete_post( $sample_page->ID, true );
    }
}

function wpn_get_page_by_title( $page_title, $output = OBJECT, $post_type = 'page' ) {
	$query = new WP_Query(
		array(
			'post_type'              => $post_type,
			'title'                  => $page_title,
			'post_status'            => 'all',
			'posts_per_page'         => 1,
			'no_found_rows'          => true,
			'ignore_sticky_posts'    => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'orderby'                => 'date',
			'order'                  => 'ASC',
		)
	);

	if ( ! empty( $query->post ) ) {
		$_post = $query->post;

		if ( ARRAY_A === $output ) {
			return $_post->to_array();
		} elseif ( ARRAY_N === $output ) {
			return array_values( $_post->to_array() );
		}

		return $_post;
	}

	return null;
}

add_action('admin_init', function(){});

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

function starter_wp_remove_simple_css_metabox_from_all_post_types() {
    $post_types = get_post_types();

    // Exclude a specific post type from the removal process (e.g., 'page')
    $exclude_post_type = '';
    if (($key = array_search($exclude_post_type, $post_types)) !== false) {
        unset($post_types[$key]);
    }

    foreach ($post_types as $post_type) {
        remove_meta_box('simple_css_metabox', $post_type, 'normal');
    }
}
add_action('add_meta_boxes', 'starter_wp_remove_simple_css_metabox_from_all_post_types');

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

// Disable directory browsing
add_action( 'admin_init', 'swp_modify_htaccess_functions' );
function swp_modify_htaccess_functions(){
  if( !is_multisite() ){
    require_once( ABSPATH . 'wp-admin/includes/file.php' );

    $home_path = get_home_path();
    $htaccess_location = $home_path . '.htaccess';

    $content = array(
      'Options -Indexes'
    );
	
    insert_with_markers( $htaccess_location, 'emn_edit', $content );
  }
}



function swp_delete_files_and_show_notice() {
    include_once ABSPATH . 'wp-admin/includes/file.php';
    WP_Filesystem();
    global $wp_filesystem;

    // List of files to be deleted
    $files_to_delete = array(
        WP_PLUGIN_DIR . '/akismet/akismet.php',
        WP_PLUGIN_DIR . '/hello.php'
    );

    // Loop through each file and attempt deletion
    foreach ($files_to_delete as $file) {
        if ($wp_filesystem->exists($file)) {
            $wp_filesystem->delete($file);
        }
    }

    // Delete parent directory of Akismet plugin if it exists
    $akismet_parent_dir = WP_PLUGIN_DIR . '/akismet';
    if ($wp_filesystem->is_dir($akismet_parent_dir)) {
        $wp_filesystem->delete($akismet_parent_dir, true);
    }

    // Define themes to delete
    $themes_to_delete = array(
        get_theme_root() . '/twentytwentythree',
        get_theme_root() . '/twentytwentytwo'
    );

    // Loop through each theme and attempt deletion
    foreach ($themes_to_delete as $theme) {
        if ($wp_filesystem->is_dir($theme)) {
            $wp_filesystem->delete($theme, true);
        }
    }

    // Set option to indicate that admin notice should be displayed
    update_option('swp_delete_files_and_show_notice', true);    
}

// Hook into admin_notices to display admin notice
add_action('admin_notices', 'swp_display_admin_notice');

function swp_display_admin_notice() {
    if (get_option('swp_delete_files_and_show_notice', false)) {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('Akismet, Hello Dolly plugins & Twenty Twenty Two, Twenty Twenty Three themess removed successfully !!!', 'custom-plugin-domain'); ?></p>
        </div>
        <?php
        delete_option('swp_delete_files_and_show_notice');
    }
}