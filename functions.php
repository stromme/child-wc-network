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

function hs_mailchimp_tracking_tag() {
	echo "
<script type=\"text/javascript\">
  var \$mcGoal = {'settings':{'uuid':'ff82113449','dc':'us6'}};
	(function() {
    var sp = document.createElement('script'); sp.type = 'text/javascript'; sp.async = true; sp.defer = true;
    sp.src = ('https:' == document.location.protocol ? 'https://s3.amazonaws.com/downloads.mailchimp.com' : 'http://downloads.mailchimp.com') + '/js/goal.min.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(sp, s);
  })();
</script>
";
}
add_action('wp_head', 'hs_mailchimp_tracking_tag');

function add_google_map_js() {
  global $wp_query;
  $template_name = get_post_meta( $wp_query->post->ID, '_wp_page_template', true );
  if($template_name=='page-templates/page-locations.php'){
    wp_register_script( 'google-map-api', 'http'.(isset($_SERVER['HTTPS'])?'s':'').'://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDsgeBIjdh92uqzr0jWMHz_2YRljj_4sxc'/*?key=AIzaSyAkeTjrQLIwVYy8ScXJEoubUrrg0X7OYpU*/);
    wp_enqueue_script( 'google-map-api' );
    wp_register_script( 'wc-locations', get_stylesheet_directory_uri().'/js/locations.js', array('jquery'));
    wp_enqueue_script( 'wc-locations' );
  }
}
add_action('wp_head', 'add_google_map_js');

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
  $status = 1;
  $message = 'Success';
  $html = '';
  $markers = array();
  $map_center = '';

  if(defined('W3TC_LIB_W3_DIR') || (!defined('W3TC_LIB_W3_DIR') && wp_verify_nonce((isset($_REQUEST['_nonce'])?$_REQUEST['_nonce']:''), 'select-location-'.date('Ymd')))){
    global $wpdb;

    $country_states = tb_get_states($_REQUEST['country']);
    $country_name = tb_get_countries($_REQUEST['country']);
    $state = $country_states[$_REQUEST['state_province']];
    $wpdb->query("SELECT l.id, l.city, z.blog_id FROM tb_locations l, tb_zip_codes z WHERE l.province='".$_REQUEST['state_province']."' AND l.id=z.location_id AND z.blog_id<>0 GROUP BY l.id ORDER BY l.id ASC");
    $results = $wpdb->last_result;
    $exist = 0;
    if(count($results)>0){
      foreach($results as $result){
        $details = get_blog_details( $result->blog_id, false );
        if($details && ($details->deleted!=1 || $details=='')){
          $company = get_blog_option($result->blog_id, 'tb_company');
          $location_term = strtolower(str_replace(' ','-',$result->city.' '.$_REQUEST['state_province']));
          switch_to_blog($result->blog_id);
          $locations = get_posts(array(
            'post_type' => 'cftl-tax-landing',
            'post_status' => 'publish',
            'tax_query' => array(
              array(
              'taxonomy' => 'locations',
              'field' => 'slug',
              'terms' => $location_term)
            ))
          );
          restore_current_blog();
          $url = get_blogaddress_by_id($result->blog_id);
          if(count($locations)>0){
            $url .= 'locations/'.$locations[0]->post_name.'/';
          }
          $map_data = get_map_data($result->city.','.$state.','.$country_name);
          $lat = '';
          $lng = '';
          try {
            $json_map = json_decode($map_data, true);
            if($json_map['status']=='OK'){
              $lat = isset($json_map['results'][0]['geometry']['location']['lat'])?$json_map['results'][0]['geometry']['location']['lat']:'';
              $lng = isset($json_map['results'][0]['geometry']['location']['lng'])?$json_map['results'][0]['geometry']['location']['lng']:'';
              $marker = array(
                'city' => $result->city,
                'state' => $state,
                'company_name' => $company['name'],
                'lat' => $lat,
                'lng' => $lng
              );
              array_push($markers, $marker);
            }
          }catch(Exception $e){}
          $html .= '<li><a data-lat="'.$lat.'" data-lng="'.$lng.'" href="'.$url.'" target="_blank"><span class="city">'.$result->city.',</span> <span class="state">'.$state.'</span><span class="company-name">'.(isset($company['name'])?$company['name']:'').'</span><span class="arrow"></span></a></li>';
          $exist++;
        }
      }
    }

    if($exist==1){
      $map_center = array('lat'=>$markers[0]['lat'], 'lng'=>$markers[0]['lng']);
    }
    else {
      $center = get_map_data($state.','.$country_name);
      try {
        $json_center = json_decode($center, true);
        if($json_center['status']=='OK'){
          $map_center = array(
            'lat' => isset($json_center['results'][0]['geometry']['location']['lat'])?$json_center['results'][0]['geometry']['location']['lat']:'',
            'lng' => isset($json_center['results'][0]['geometry']['location']['lng'])?$json_center['results'][0]['geometry']['location']['lng']:''
          );
        }
      }catch(Exception $e){}
      if($exist==0){
        $html = '<li><span class="text center">We\'re sorry, it looks like we don\'t service that area yet. If you\'re a window cleaner or know a window cleaner that stands out as <strong>the best</strong> in this region, <a href="#" class="member-apply">let us know.</a></span></li>';
      }
    }
  }
  else {
    $status = 0;
    $message = 'Verification error';
  }
  die(json_encode(array(
    'status'   => $status,
    'message'  => $message,
    'html'     => $html,
    'map_center' => $map_center,
    'markers'  => $markers,
    'pin'      => get_stylesheet_directory_uri().'/images/map-pin.png'
  )));
}
add_action('wp_ajax_select_windowcleaning_location', 'select_windowcleaning_location');
add_action('wp_ajax_nopriv_select_windowcleaning_location', 'select_windowcleaning_location');

function get_map_data($search){
  $curl = curl_init();
  $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($search);
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
  curl_setopt($curl, CURLOPT_HEADER, 0);
  $result = curl_exec($curl);
  curl_close($curl);
  return $result;
}

?>