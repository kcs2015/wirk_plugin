<?php

/*
Plugin Name: Who Is Richard Knight Plugin
Plugin URI: https://whoisrichardknight.com/
Description: Does great things
Version: 0.1.0
Author: Richard Knight
Author URI: https://whoisrichardknight.com/
Text Domain: wirk-plugin

*/

/**
 *
 */
function wirkplugin_activate() {

    create_demo_sample_posttype();
}
register_activation_hook( __FILE__, 'wirkplugin_activate' );

// Our custom post type function
function create_demo_sample_posttype() {

    register_post_type( 'demo_sample',
        // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Demo Sample' ),
                'singular_name' => __( 'Demo Samples' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'demo_sample'),
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_demo_sample_posttype' );


function remove_demo_sample_posttype(){
    unregister_post_type("demo_sample");
}

register_deactivation_hook(__FILE__, 'remove_demo_sample_posttype');

//register_uninstall_hook( );
?>