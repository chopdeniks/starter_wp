<?php

add_filter('admin_init', 'swp_register_my_general_settings_fields');
function swp_register_my_general_settings_fields(){
	register_setting('general', 'swp_settings');

    /* Create settings section */
    add_settings_section(
        'swp_general_settings_id',
        'Starterwp Settings',
        'swp_general_settings_section_description',
        'general'
    );	
    /* add field */
	add_settings_field(
	    'swp_settings[disable_admin_bar]', 
	    '<label for="swp_settings[disable_admin_bar]">'.__('Disable Admin bar' , 'swp_settings[disable_admin_bar]' ).'</label>' , 
	    'swp_general_settings_custom_fields_html', 
	    'general',
	    'swp_general_settings_id'
	    );
}

/* Setting Section Description */
function swp_general_settings_section_description(){
    echo '<hr style=" margin-bottom: -13px; margin-top: -3px; ">';
}


function swp_general_settings_custom_fields_html(){
	$swp_options = get_option( 'swp_settings' );
	?>
	
	<input type='checkbox' name='swp_settings[disable_admin_bar]' <?php checked( $swp_options['disable_admin_bar'], 1 ); ?> value='1'>
	
	<?php
}
