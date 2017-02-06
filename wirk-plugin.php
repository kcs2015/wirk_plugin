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

// Add taxonomies to Demo Sample
function demo_sample_taxonomies() {
    $labels = array(
        'name'              => _x( 'Demo Categories', 'taxonomy general name' ),
        'singular_name'     => _x( 'Demo Category', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Demo Categories' ),
        'all_items'         => __( 'All Demo Categories' ),
        'parent_item'       => __( 'Parent Demo Category' ),
        'parent_item_colon' => __( 'Parent Demo Category:' ),
        'edit_item'         => __( 'Edit Demo Category' ),
        'update_item'       => __( 'Update Demo Category' ),
        'add_new_item'      => __( 'Add New Demo Category' ),
        'new_item_name'     => __( 'New Demo Category' ),
        'menu_name'         => __( 'Demo Categories' ),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
    );
    register_taxonomy( 'demo_sample_category', 'demo_sample', $args );
}
add_action( 'init', 'demo_sample_taxonomies', 0 );

// ADD Metaboxes

add_action( 'add_meta_boxes', 'demo_sample_meta_boxes',10, 2 );
function demo_sample_meta_boxes($p_type, $curr_post) {
    // Get Saved Meta
    $GLOBALS["saved_demo_meta"] = get_post_meta( $curr_post->ID );

    add_meta_box(
        'demo_description_box',
        __( 'Demo Description', 'myplugin_textdomain' ),
        'demo_description_box_content',
        'demo_sample',
        'normal',
        'low'
    );

    add_meta_box(
        'demo_instruction_box',
        __( 'Demo Instruction', 'myplugin_textdomain' ),
        'demo_instruction_box_content',
        'demo_sample',
        'normal',
        'low'
    );

    add_meta_box(
        'demo_shortcode_box',
        __( 'Demo Shortcode', 'myplugin_textdomain' ),
        'demo_shortcode_box_content',
        'demo_sample',
        'side',
        'low'
    );
}

function demo_description_box_content( $post ) {

    wp_nonce_field( 'update_demo_description', 'demo_description_box_nonce' );

    $saved_demo_description = '';
    // Get Saved Meta
    if (isset($GLOBALS["saved_demo_meta"]['demo_description'][0])){
        $saved_demo_description = $GLOBALS["saved_demo_meta"]['demo_description'][0] ;
    }

    echo 'Enter a quick description of what the demo sample does: <br><textarea id="demo_description" name="demo_description" rows="4" cols="100">' .$saved_demo_description .'</textarea>';
}
function demo_instruction_box_content( $post ) {

    wp_nonce_field( 'update_demo_instruction', 'demo_instruction_box_nonce' );

    //Get Saved Meta
    $saved_demo_instruction ='';
    if (isset($GLOBALS["saved_demo_meta"]['demo_instruction'][0])){
        $saved_demo_instruction = $GLOBALS["saved_demo_meta"]['demo_instruction'][0] ;
    }

  echo 'Enter instructions explaining to the the visitor how use the demo: <br><textarea id="demo_instruction" name="demo_instruction" rows="4" cols="100">' .$saved_demo_instruction .' 
</textarea>';
}
function demo_shortcode_box_content( $post ) {
    wp_nonce_field( 'update_demo_shortcode', 'demo_shortcode_box_nonce' );

    //Get Saved Meta
    $saved_demo_shortcode ='';
    if (isset($GLOBALS["saved_demo_meta"]['demo_shortcode'][0])){
        $saved_demo_shortcode = $GLOBALS["saved_demo_meta"]['demo_shortcode'][0] ;
    }

    echo 'Create a shortcode id for this the demo sample (i.e. - sku-cat-page-insert): <br><label for="demo_shortcode"></label>';
    echo '<input type="text" id="demo_shortcode" name="demo_shortcode" placeholder="Enter the shortcode" value="' .$saved_demo_shortcode .'" />';
}

/**
 * Save post metadata when a post is saved.
 *
 * @param int $post_id The post ID.
 * @param post $post The post object.
 * @param bool $update Whether this is an existing post being updated or not.
 */
function demo_sample_save_meta( $post_id, $post, $update ) {

    /*
     * In production code, $slug should be set only once in the plugin,
     * preferably as a class property, rather than in each function that needs it.
     */
    $curr_post_type = get_post_type($post_id);

    // If this isn't a 'demo_sample' post, don't update it.
   if ( 'demo_sample' == $curr_post_type ) {


        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;

       if(isset($_POST['demo_description_box_nonce'])) {
           if (wp_verify_nonce($_POST['demo_description_box_nonce'], 'update_demo_description')) {
               // - Update the post's metadata.
               update_post_meta($post_id, 'demo_description', sanitize_text_field($_POST['demo_description']));
           }
       }
       if(isset($_POST['demo_instruction_box_nonce'])) {
           if (wp_verify_nonce($_POST['demo_instruction_box_nonce'], 'update_demo_instruction')) {
               // - Update the post's metadata.
               update_post_meta($post_id, 'demo_instruction', sanitize_text_field($_POST['demo_instruction']));
           }
       }
       if(isset($_POST['demo_shortcode_box_nonce'])) {
           if (wp_verify_nonce($_POST['demo_shortcode_box_nonce'], 'update_demo_shortcode')) {
               // - Update the post's metadata.
               update_post_meta($post_id, 'demo_shortcode', sanitize_text_field($_POST['demo_shortcode']));
           }
       }
           /* if (isset($_POST['demo_description'])) {
                update_post_meta($post_id, 'demo_description', sanitize_text_field($_POST['demo_description']));
            }
        */


    }   // END IF - DEMO SAMPLE

    /*if ( 'page' == $curr_post_type ) {
        if ( !current_user_can( 'edit_page', $post_id ) )
            return;
    } else {
        if ( !current_user_can( 'edit_post', $post_id ) )
            return;
    }*/

   /* $product_price = $_POST['product_price'];
update_post_meta( $post_id, 'product_price', $product_price );*/


} // END FUNCTION


add_action( 'save_post', 'demo_sample_save_meta', 10, 3 );

/* ==============================*/
/* ======= WOOCOMMERCE UPDATES =======*/
/* ==============================*/

/* ADD COLUMN TO WOOCOMMERCE */



?>