<?php

require_once dirname( __FILE__ ) . '/tgmpa/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'eka_register_required_plugins' );

function eka_register_required_plugins() {

	$plugins = array(

		// include a plugin from the WordPress Plugin Repository.
		array(
			'name'      => 'Simple CSS',
			'slug'      => 'simple-css',
			'required'  => false,
		),	
		array(
			'name'      => 'Easy remove item menu',
			'slug'      => 'easy-remove-item-menu',
			'required'  => false,
		),	
		array(
			'name'      => 'Really Simple SSL',
			'slug'      => 'really-simple-ssl',
			'required'  => false,
		),	
		array(
			'name'      => 'Classic Widgets',
			'slug'      => 'classic-widgets',
			'required'  => false,
		),	
		array(
			'name'      => 'Classic Editor',
			'slug'      => 'classic-editor',
			'required'  => false,
		),
		array(
			'name'      => 'Duplicate Page',
			'slug'      => 'duplicate-page',
			'required'  => false,
		),		


	);

	$config = array(
		'id'           => 'eka',                 
		'default_path' => '',                      
		'menu'         => 'tgmpa-install-plugins', 
		'parent_slug'  => 'plugins.php',            
		'capability'   => 'manage_options',    
		'has_notices'  => true,                    
		'dismissable'  => true,                    
		'dismiss_msg'  => '',                      
		'is_automatic' => false,                   
		'message'      => '',                   
    
    'strings'      => array(
        'notice_can_install_recommended'  => _n_noop(
				'Recommended following plugin: %1$s.',
				'Recommended following plugins: %1$s.',
				'eka'
			),
		),	
	);

	tgmpa( $plugins, $config );
}
