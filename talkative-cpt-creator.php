<?php 
  /*
  Plugin Name: Talkative CPT Creator
  Plugin URI: 
  Description: Talkative Custom Post Type Creator!
  Author: Talkative
  Version: 0.0.1
  Author URI: http://talkative.se/
  */

  defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

  add_action( 'init', 'talkative_create_post_type' );
  function talkative_create_post_type() {
    $flippinConfig = array(
      'menu_icon' => 'dashicons-heart',
      'labels' => array(
        'name' => __( 'Products' ),
        'singular_name' => __( 'Product' )
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'products')
    );

    register_post_type( 'flippin_product', $flippinConfig);
  }

  // custom taxonomy
  function talkative_create_taxonomy() {
    register_taxonomy(
      'flippin_product_cat',
      'flippin_product',
      array(
        'label' => __( 'Categories' ),
        'rewrite' => array( 'slug' => 'filter' ),
        'hierarchical' => true
      )
    );

    register_taxonomy_for_object_type( 'flippin_product_cat', 'flippin_product' );
  }
  add_action( 'init', 'talkative_create_taxonomy' );

  function talkative_rewrite_flush() {
      talkative_create_post_type();
      flush_rewrite_rules();
  }
  register_activation_hook( __FILE__, 'talkative_rewrite_flush' );

?>