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

// Disable Elementor Tracker Notice and Tracking
add_action( 'init', function() {
    // Set the tracker notice as already dismissed
    update_option( 'elementor_tracker_notice', '1' );
    
    // Disable tracking by preventing the tracker from being allowed
    if ( class_exists( 'Elementor\Plugin' ) ) {
        remove_action( 'admin_notices', [ \Elementor\Plugin::instance()->admin, 'notice_tracker' ] );

        // Optional: Automatically opt-out of tracking
        update_option( 'elementor_allow_tracking', 'no' );
    }
});

// Remove the metabox for Revolution Slider
add_action('do_meta_boxes', function() {
  // Check if the Revolution Slider plugin is installed
  if (class_exists('RevSlider')) {
    // Get all public post types dynamically
    $post_types = get_post_types(array('public' => false), 'names');

    // Loop through all public post types and remove the Revolution Slider metabox
    foreach ($post_types as $post_type) {
      remove_meta_box('slider_revolution_metabox', $post_type, 'side');
    }
  }
}, 100);


function swp_override_admin_inline_css() {  ?>
<style type="text/css">
.postbox .handle-order-higher, 
.postbox .handle-order-lower, 
.postbox .handlediv {
    display: none!important;
}
.postbox .postbox-header {
    pointer-events: none!important;
}
#screen-meta-links .show-settings:hover {
    opacity: 1;
}
#screen-meta-links .show-settings {
    opacity: 0;
    transition: opacity 0.3s linear 0.1s;
    transition: opacity 0.3s;
}
</style>
<?php 
}

add_action( 'admin_head', 'swp_override_admin_inline_css' );