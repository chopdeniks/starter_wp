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

</style>
<?php 
}

add_action( 'admin_head', 'swp_override_admin_inline_css' );