<?php 
  /*
  Plugin Name: Talkative CPT Creator
  Plugin URI: 
  Description: Talkative Custom Post Type Creator!
  Author: Talkative
  Version: 0.0.1
  Author URI: http://talkative.se/
  Text Domain: tk
  */

  defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

  function tk_create_post_type() {
    $flippinConfig = array(
      'menu_icon' => 'dashicons-heart',
      'labels' => array(
        'name' => __( 'Flippin', 'tk' ),
        'singular_name' => __( 'Flippin', 'tk' )
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'flippin'),
      'supports' => array('title')
    );

    register_post_type( 'flippin_post', $flippinConfig);
  }
  add_action( 'init', 'tk_create_post_type' );

  function tk_create_taxonomy() {
    register_taxonomy(
      'flippin_post_cat',
      'flippin_post',
      array(
        'label' => __( 'Categories', 'tk' ),
        'rewrite' => array( 'slug' => 'filter' ),
        'hierarchical' => true
      )
    );

    register_taxonomy_for_object_type( 'flippin_post_cat', 'flippin_post' );
  }
  add_action( 'init', 'tk_create_taxonomy' );

  function tk_adding_custom_meta_boxes( $post ) {
    add_meta_box( 
      'tk-attachment-metabox-id',
      __( 'Attachment', 'tk' ),
      'tk_attachment_metabox_render_inner',
      'flippin_post',
      'normal',
      'default'
    );
  }
  add_action( 'add_meta_boxes_flippin_post', 'tk_adding_custom_meta_boxes' );

  function tk_attachment_metabox_render_inner( $post ){
    echo '<form method="post">';
      wp_nonce_field( 'tk_attachment_metabox_render_URL', 'tk_attachment_metabox_render_URL_nonce', true, true );
      $tkAttachmentUrlVal = get_post_meta( $post->ID, '_tk_attachment_metabox_value_key', true );
      echo '<input type="text" id="tk_attachment_URL" name="tk_attachment_URL" value="' . esc_attr( $tkAttachmentUrlVal ) . '" style="width: 100%;">';
    echo '</form>';
  }

  function tk_save_metabox_data( $post_id ){
    // if this fails, check_admin_referer() will automatically print a "failed" page and die.
    if ( ! empty( $_POST ) && check_admin_referer( 'tk_attachment_metabox_render_URL', 'tk_attachment_metabox_render_URL_nonce' ) ) {
      if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
        return $post_id;
      }

      // Check user permissions
      if ( 'page' == $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_page', $post_id ) ){
          return $post_id;
        }
      } else if( 'post' == $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_post', $post_id ) ){
          return $post_id;
        }
      }

      // Update post meta
      $tk_attachment_URL = sanitize_text_field( $_POST['tk_attachment_URL'] );
      update_post_meta( $post_id, '_tk_attachment_metabox_value_key', $tk_attachment_URL );
    }
  }
  add_action( 'save_post', 'tk_save_metabox_data' );

  function tk_rewrite_flush() {
      tk_create_post_type();
      flush_rewrite_rules();
  }
  register_activation_hook( __FILE__, 'tk_rewrite_flush' );








?>