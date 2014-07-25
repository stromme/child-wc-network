<?php
/**
 * Name: WC Network Theme Functions
 * Description: 
 *
 * @package Playground
 * @author Hatch
 */
 
/**
 * Register bulletin custom post type
 * Bulletins are used in a feed on the Toolbox Dashboard
 *
 * @since 0.0.1
 */

function tb_register_bulletin() {
	register_post_type('bulletin',
		array(
			'labels' => array(
				'name' => __('Bulletins'),
				'singular_name' => __('Bulletin'),
			),
			// 'menu_position' => 5,
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'publicly_queryable' => true,
			'rewrite' => array('slug' => 'toolbox'),
			'supports' => array('title', 'editor', 'thumbnail'),
			'hierarchical' => true,
			'map_meta_cap' => true,
			'capability_type' => 'post'
		)
	);
}

add_action('init', 'tb_register_bulletin');

function hs_google_publisher() {
	echo '<link href="https://plus.google.com/110109148861996385232" rel="publisher" />';
}
add_action('wp_head', 'hs_google_publisher');

/**
 * Ajax function to find location
 */
function find_windowcleaning_location(){
  $status_code = 0;
  $status_message = "Verification error";
  $address = home_url().'/locations/?find';
  if(defined('W3TC_LIB_W3_DIR') || (!defined('W3TC_LIB_W3_DIR') && wp_verify_nonce((isset($_REQUEST['_nonce'])?$_REQUEST['_nonce']:''), 'find-location-'.date('Ymd')))){
    global $wpdb;
    $zip = $_REQUEST['zip'];
    $wpdb->query("SELECT * FROM tb_zip_codes WHERE zip='".$zip."' ORDER BY id ASC");
    $blog_id = '';
    $results = $wpdb->last_result;
    if(count($results)>0){
      $result = $results[0];
      $blog = $result->blog_id;
      if($blog>0 && false){
        $blog_id = $blog;
      }
      else {
        $location_id = $result->location_id;
        $wpdb->query("SELECT blog_id FROM tb_zip_codes WHERE location_id='".$location_id."' GROUP BY location_id ASC");
        $results = $wpdb->last_result;
        if(count($results)>0){
          $blog = $result->blog_id;
          if($blog>0){
            $blog_id = $blog;
          }
        }
      }
    }
    $status_code = 1;
    $status_message = '';
    if($blog_id!=''){
      $blog = get_blogaddress_by_id($blog_id);
      if(strlen($blog)>8){
        $status_message = "You will be redirected to the closest location...";
        $address = $blog;
      }
    }
  }

  die(json_encode(array(
    'status' => $status_code,
    'status_message' => $status_message,
    'address' => $address
  )));
}
add_action('wp_ajax_find_windowcleaning_location', 'find_windowcleaning_location');
add_action('wp_ajax_nopriv_find_windowcleaning_location', 'find_windowcleaning_location');

/**
 * Ajax function to select location
 */
function select_windowcleaning_location(){
  if(defined('W3TC_LIB_W3_DIR') || (!defined('W3TC_LIB_W3_DIR') && wp_verify_nonce((isset($_REQUEST['_nonce'])?$_REQUEST['_nonce']:''), 'select-location-'.date('Ymd')))){
    global $wpdb;

    $wpdb->query("SELECT l.id, l.city, z.blog_id FROM tb_locations l, tb_zip_codes z WHERE l.province='".$_REQUEST['state_province']."' AND l.id=z.location_id AND z.blog_id<>0 GROUP BY l.id ORDER BY l.id ASC");
    echo "<!-- ";
    var_dump("SELECT l.id, l.city, z.blog_id FROM tb_locations l, tb_zip_codes z WHERE l.province='".$_REQUEST['state_province']."' AND l.id=z.location_id AND z.blog_id<>0 GROUP BY l.id ORDER BY l.id ASC");
    echo " -->";
    $results = $wpdb->last_result;
    if(count($results)>0){
      foreach($results as $result){
        $details = get_blog_details( $result->blog_id, false );
        if($details->deleted!=1){
          echo '<li><a href="'.get_blogaddress_by_id($result->blog_id).'" class="label-city"><h3>'.$result->city.'</h3>'.($delete?'.':'').'</a></li>';
        }
      }
    }
    else {
      echo '<strong>Looks like we don\'t service that area yet.</strong>';
    }
    die();
  }
  else {
    die('Verification error');
  }
}
add_action('wp_ajax_select_windowcleaning_location', 'select_windowcleaning_location');
add_action('wp_ajax_nopriv_select_windowcleaning_location', 'select_windowcleaning_location');

?>