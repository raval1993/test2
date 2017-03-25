<?php
/**
 * Loads the WordPress environment and template.
 *
 * @package WordPress 
 */

if ( !isset($wp_did_header) ) {

	$wp_did_header = true;

	// Load the WordPress library.
	require_once( dirname(__FILE__) . '/wp-load.php' );

	// Set up the WordPress query.
	wp();
	// Load the theme template.
	

	global $current_site;
	  global $wpdb;
	  $url[]="http://bglin.com/admin/index.php?controller=pjAPI&action=pjGetAll";
	  $url[]="http://bglin.com/admin/index.php?controller=pjAPI&action=pjGetFeatured";
	  //$url[]="http://bglin.com/admin/index.php?controller=pjAPI&action=pjGet&id=16";

	  foreach($url as $urls){
	$json = file_get_contents($urls);
	$objs = json_decode($json);
	foreach($objs as $obj){

	$posttitl = explode(' ',$obj->title);
	$postname=implode('-',$posttitl);

	// Create post object
	$title_from_soap=$obj->title;
	if($title_from_soap){
	//$return = $wpdb->get_row( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $title_from_soap . "' && post_status = 'publish' && post_type='properties' ", 'ARRAY_N' );
  //print_r($return);
	$return_pid1 = $wpdb->get_row( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'wp_rem_post_loc_latitude_property' && meta_value = '".$obj->lat."' ", 'ARRAY_N' );
	$return_pid2 = $wpdb->get_row( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'wp_rem_post_loc_longitude_property' && meta_value = '".$obj->lng."' ", 'ARRAY_N' );
	//$postlat = get_post_meta( $return[0], 'wp_rem_post_loc_latitude_property', true );
	//$postlong = get_post_meta( $return[0], 'wp_rem_post_loc_longitude_property', true );
	//$df = 0
	//print_r($return_pid1); echo "/n".print_r($return_pid2); die;
	if(($return_pid1[0] != $return_pid2[0]) || $return_pid1[0] == ''){
	    $galleryimages =array();
	 foreach($obj->images as $gimages){
	   $newpostid =  crb_insert_attachment_from_url($gimages);
	   $galleryimages[]=$newpostid;
	 }
	 $end = date('Y-m-d', strtotime('+3 years'));
	$my_post = array(
	  'post_title'    => $obj->title,
	  'post_content'  => $obj->description,
	  'post_status'   => 'publish',
	  'post_excerpt'   => $obj->rightmove_description,
	  'comment_status'   => 'publish',
	  'post_type'   => properties,
	  'post_parent'   => 0,
	  'post_author'   => 1,
	  'post_date'  => $obj->created,
	  'post_modified' => $obj->modified,
	  'post_name'   => $postname
	);
	$t='';
	if($obj->is_featured=='T'){
	    $t= 'on';
	}
	$staus ='';
	if($obj->status=='T'){
	 $staus='active';
	}
	$MONTH= 'variant_month';
	if($obj->price_per!='month'){
	    $MONTH='variant_week';
	}
	$myArr='';
	if($obj->is_student=='T'){
	$myArr[]='students' ;
	}
	$date = date_create();
	$timestamp = date_timestamp_get($date);
	$end = date('Y-m-d', strtotime('+3 years'));
	$test = new DateTime($end);
	$timestampexp = date_timestamp_get($test);
	$tags[]="rent";
	$tags[]="share";
	// Insert the post into the database
	$insertid = wp_insert_post( $my_post );
	  update_post_meta( $insertid, 'wp_rem_tags', $tags );
	   update_post_meta( $insertid, 'wp_rem_property_tags', $tags );
	   update_post_meta( $insertid, 'wp_rem_detail_page_gallery_ids', $galleryimages );
	   update_post_meta( $insertid, 'wp_rem_property_expired', $timestampexp );
	   update_post_meta( $insertid, 'wp_rem_transaction_property_pic_num', count($galleryimages) );
	   update_post_meta( $insertid, 'wp_rem_property_detail_page', 'detail_view3' );
	   update_post_meta( $insertid, 'wp_rem_property_views_count', $obj->views );
	   update_post_meta( $insertid, 'wp_rem_property_type', 'students' );
	   update_post_meta( $insertid, 'wp_rem_property_status', 'active' );
	   update_post_meta( $insertid, 'wp_rem_property_posted', $timestamp );
	   update_post_meta( $insertid, 'wp_rem_property_is_featured', $t );
	   update_post_meta( $insertid, 'property_member_status', $staus );
	   update_post_meta( $insertid, 'wp_rem_property_price', $obj->price );
	   update_post_meta( $insertid, 'wp_rem_price_type', $MONTH );
	   update_post_meta( $insertid, 'wp_rem_post_loc_state_property', $obj->address_state );
	   update_post_meta( $insertid, 'wp_rem_post_loc_city_property', $obj->address_city );
	   update_post_meta( $insertid, 'wp_rem_post_loc_country_property', $obj->address_country );
	   update_post_meta( $insertid, 'wp_rem_post_loc_address_property', $obj->address_1 );
	   update_post_meta( $insertid, 'wp_rem_post_loc_latitude_property', $obj->lat );
	   update_post_meta( $insertid, 'wp_rem_post_loc_longitude_property', $obj->lng );
	   update_post_meta( $insertid, 'tenants-type', $myArr );
	   update_post_meta( $insertid, 'wp_rem_enable_yelp_places_element','on' );
	   update_post_meta( $insertid, 'wp_rem_loc_radius_property', 'on' );
	   update_post_meta( $insertid, 'wp_rem_post_loc_country_property', $obj->address_city );
	   }else{

	   }
	   }
	   }
	   }
require_once( ABSPATH . WPINC . '/template-loader.php');

}
