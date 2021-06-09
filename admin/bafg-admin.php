<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit();
}

/*
* Enqueue css and js for bafg
*/
add_action( 'admin_enqueue_scripts', 'bafg_admin_enqueue_scripts' );

//Enqueue script in admin area
function bafg_admin_enqueue_scripts(){
    wp_enqueue_script( 'custom_js', plugins_url( '../assets/js/bafg-script.js', __FILE__ ), array('jquery'), null, true );

    wp_enqueue_style('bafg_admin_style', plugins_url( '../assets/css/bafg-admin-style.css', __FILE__ ));
}

// admin column
add_filter('manage_bafg_posts_columns', 'bafg_custom_columns', 10);  
add_action('manage_posts_custom_column', 'bafg_custom_columns_bimage', 10, 2);  
add_action('manage_posts_custom_column', 'bafg_custom_columns_aimage', 10, 2);  
add_action('manage_posts_custom_column', 'bafg_custom_columns_shortcode', 10, 2);  

function bafg_custom_columns($columns) {
   $columns = array(
      'cb' => '<input type="checkbox" />',
      'title' => esc_html__('Title', 'bafg'),
      'shortcode' => esc_html__('Shortcode', 'bafg'),
      'bimage' => esc_html__('Before Image', 'bafg'),
      'aimage' => esc_html__('After Image', 'bafg'),
      'date' => __( 'Date' )
   );
  return $columns;
}

function bafg_custom_columns_bimage($column_name, $id){  
  if($column_name === 'bimage') {
      
      $bafg_before_after_method = !empty(get_post_meta($id, 'bafg_before_after_method', true)) ? get_post_meta($id, 'bafg_before_after_method', true) : 'method_1';
      
      if( is_plugin_active( 'beaf-before-and-after-gallery-pro/before-and-after-gallery-pro.php' ) ){
          
          if($bafg_before_after_method == 'method_2'){
          
              $image_url = get_post_meta($id, 'bafg_before_after_image', true);

          }else{

              $image_url = get_post_meta($id, 'bafg_before_image', true);
          }
      }else{
          $image_url = get_post_meta($id, 'bafg_before_image', true);
      }
  	
  	 $image_id = attachment_url_to_postid( $image_url );
  	 $before_image = wp_get_attachment_image( $image_id, 'thumbnail');
  	 echo $before_image;
  }
}

function bafg_custom_columns_aimage($column_name, $id){  
  if($column_name === 'aimage') {
      
      $bafg_before_after_method = !empty(get_post_meta($id, 'bafg_before_after_method', true)) ? get_post_meta($id, 'bafg_before_after_method', true) : 'method_1';
      
      if( is_plugin_active( 'beaf-before-and-after-gallery-pro/before-and-after-gallery-pro.php' ) ){
          
          if($bafg_before_after_method == 'method_2'){
          
              $image_url = get_post_meta($id, 'bafg_before_after_image', true);

          }else{

              $image_url = get_post_meta($id, 'bafg_after_image', true);
          }
      }else{
          $image_url = get_post_meta($id, 'bafg_after_image', true);
      }
      
  	 $image_id = attachment_url_to_postid( $image_url );
  	 $after_image = wp_get_attachment_image( $image_id, 'thumbnail');
  	 echo $after_image;
  }
}

function bafg_custom_columns_shortcode($column_name, $id){  
  if($column_name === 'shortcode') { 
   $post_id =	$id;
   $shortcode = 'bafg id="' . $post_id . '"';
      echo '[' . $shortcode .']';   
  }  
}

/*
* Adding gallery column
*/
add_filter("manage_edit-bafg_gallery_columns", 'bafg_gallery_columns' ); 
add_filter('manage_bafg_gallery_custom_column', 'bafg_gallery_column_content', 10, 3 );
/*
* Gallery category column
*/
function bafg_gallery_columns($theme_columns) {
	$theme_columns['bafg_gallery'] = 'Gallery Shortcode';
	return $theme_columns;
}

/*
* Gallery category column content
*/
function bafg_gallery_column_content( $content, $column_name, $term_id ){
	switch ($column_name) {
		case 'bafg_gallery':
			$content = '<input class="bafg_display_shortcode" type="text" value="[bafg_gallery category='.$term_id.']" readonly onclick="bafgCopyShortcode()">';
			break;
	}
	return $content;
}

/*
* Register gallery generator page.
*/
add_action('admin_menu', 'bafg_register_gallery_generator_page' );
function bafg_register_gallery_generator_page(){
	add_submenu_page(
		'edit.php?post_type=bafg',
		__('Gallery Generator', 'bafg'),
		__('Gallery Generator', 'bafg'),
		'manage_options',
		'bafg_gallery',
		'bafg_gallery_cb'
	);
}

/*
* Gallery generator callback
*/
function bafg_gallery_cb(){
	require_once( plugin_dir_path( __FILE__ ). '../inc/templates/bafg-gallery-generator.php' );
}

/*
* Shortcode copied alert text
*/
add_action('admin_footer', function(){
    echo '<div id="bafg_copy">'.esc_html('Shortcode Copied!').'</div>';
});

/*
* Admin notice for new features
*/
function bafg_new_feature_notice() {
    $user_id = get_current_user_id();
    if ( !get_user_meta( $user_id, 'bafg_new_feature_notice_dismissed', true ) ) {
		?>
		<div class="notice notice-success">
           <h3><?php echo esc_html__( 'Introducing: Gallery Generator for Ultimate Before After Image Slider - BEAF', 'bafg' ); ?></h3>
            <p>Now, you can create 2 columns, 3 columns and 4 columns Before After Gallery. <a href="<?php echo admin_url('/edit.php?post_type=bafg&page=bafg_gallery&bafg-dismissed'); ?>">Click here</a> to go to our gallery generator. <a href="https://youtu.be/Uq3qlVdD_dY" target="_blank">Check this video</a> to learn more.</p>
            <p><a class="button" href="<?php echo admin_url('?bafg-dismissed'); ?>">Close this Notice</a></p>
        </div>
		<?php
	}
}
add_action( 'admin_notices', 'bafg_new_feature_notice' );

function bafg_new_feature_notice_dismissed() {
	$user_id = get_current_user_id();
    if ( isset( $_GET['bafg-dismissed'] ) )
        add_user_meta( $user_id, 'bafg_new_feature_notice_dismissed', 'true', true );
}
add_action( 'admin_init', 'bafg_new_feature_notice_dismissed' );