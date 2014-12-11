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
    $file = '/regions/markercluster.packed.js';
    $plugins_url = '';
    if(is_file(ABSPATH.'wp-content/plugins'.$file)){
      $plugins_url = plugins_url();
    }
    else if(is_file(ABSPATH.'wp-content/plugins/toolbox/plugins'.$file)){
      $plugins_url = plugins_url(). '/toolbox/plugins';
    }
    if($plugins_url!=''){
      wp_register_script( 'marker-cluster', $plugins_url.$file);
      wp_enqueue_script( 'marker-cluster' );
    }
    wp_register_script( 'wc-locations', get_stylesheet_directory_uri().'/js/locations.js', array('jquery'));
    wp_enqueue_script( 'wc-locations' );
    $cache_all_location = get_site_option('wc_company_locations_cache');
    $fetch = false;
    // Fetch if initial or longer than one week or when there is inconsistency of array size due to new location addition and old location removal
    if(!is_array($cache_all_location) || !isset($cache_all_location['time']) || (isset($cache_all_location['time']) && time()-$cache_all_location['time']>=(7*24*60*60))){
      $fetch = true;
    }
    global $wpdb;
    $locations = $wpdb->get_results("SELECT l.id, l.city, l.province, z.blog_id, z.location_id, z.zip FROM tb_zip_codes z, tb_locations l WHERE z.blog_id<>0 AND z.location_id=l.id GROUP BY z.location_id", ARRAY_A);
    // Check if any inconsistency
    $fetch = ($fetch || (!$fetch && count($locations)!=count($cache_all_location['locations'])));
    global $wc_locations_cache;
    if($fetch){
      $wc_locations_cache = array(
        'time' => time(),
        'locations' => array()
      );
      $wc_bloginfo = array();
      foreach($locations as $l){
        if(isset($wc_bloginfo['blog-'.$l['blog_id']])){
          $company = $wc_bloginfo['blog-'.$l['blog_id']]['company'];
          $url = $wc_bloginfo['blog-'.$l['blog_id']]['url'];
          $blog_active = $wc_bloginfo['blog-'.$l['blog_id']]['blog_active'];
        }
        else {
          $tb_company = get_blog_option($l['blog_id'], 'tb_company');
          $company = isset($tb_company['name'])?$tb_company['name']:'';
          $url = get_home_url($l['blog_id']);
          $deleted = get_blog_status($l['blog_id'], 'deleted');
          $blog_active = ($url!='' && $deleted==0);
          $wc_bloginfo['blog-'.$l['blog_id']] = array(
            'company' => $company,
            'url' => isset($url)?$url:'',
            'blog_active' => $blog_active
          );
        }
        $country=(strlen($l['zip'])<=3)?'CA':'US';
        $map_data = get_map_data($l['city'],$l['province'],$country);
        $lat = '';
        $lng = '';
        try {
          $json_map = json_decode($map_data, true);
          if($json_map['status']=='OK'){
            $lat = isset($json_map['results'][0]['geometry']['location']['lat'])?$json_map['results'][0]['geometry']['location']['lat']:'';
            $lng = isset($json_map['results'][0]['geometry']['location']['lng'])?$json_map['results'][0]['geometry']['location']['lng']:'';
          }
        }catch(Exception $e){}
        $country_states = tb_get_states($country);
        $country_name = tb_get_countries($country);
        $state_name = $country_states[$l['province']];
        $wc_locations_cache['locations']['l'.$l['id']] = array(
          'city' => $l['city'],
          'state' => $l['province'],
          'country' => $country,
          'country_name' => $country_name,
          'state_name' => $state_name,
          'company' => $company,
          'url' => $url,
          'lat' => $lat,
          'lng' => $lng,
          'del' => !$blog_active
        );
      }
      update_site_option('wc_company_locations_cache', $wc_locations_cache);
    }
    else {
      $wc_locations_cache = $cache_all_location;
    }
    uasort($wc_locations_cache['locations'], "compare_locations");
    $regions_var = array(
      'pin' => $plugins_url.'/regions/images/map-pin.png',
      'cluster_pin' => array(
        'small' => $plugins_url.'/regions/images/cluster1.png',
        'medium' => $plugins_url.'/regions/images/cluster2.png',
        'large' => $plugins_url.'/regions/images/cluster3.png'
      ),
      'wc_locations' => array_values($wc_locations_cache['locations'])
    );
    wp_localize_script('wc-locations', 'locations_var', $regions_var);
  }
}
add_action('wp_head', 'add_google_map_js');

// For order
function compare_locations($a,$b) {
  return strcmp($a['city'].', '.$a['state'], $b['city'].', '.$b['state']);
}

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
    // Oh Canada...
    if(strlen($zip)>6){
      $match = preg_match('/^([ABCEGHJKLMNPRSTVXY]{1}\d{1}[A-Z]{1}) *\d{1}[A-Z]{1}\d{1}$/', $zip, $matches);
      if($match>0) $zip = $matches[1];
    }
    $wpdb->query("SELECT * FROM tb_zip_codes WHERE zip='".$zip."' && blog_id>0 ORDER BY id ASC");
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
          $map_data = get_map_data($result->city,$_REQUEST['state_province'],$_REQUEST['country']);
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
                'lng' => $lng,
                'url' => $url
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
      $center = get_map_data('',$_REQUEST['state_province'],$_REQUEST['country']);
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

/**
 * Get map geocode based on address/parameter
 *
 * @param $address
 * @param $state
 * @param $country
 * @return mixed
 */
function get_map_data($address,$state,$country){
  $curl = curl_init();
  $query_params = array();
  if($address!=''){
    $query_params[] = 'address='.urlencode($address);
  }
  $query_params[] = 'components=administrative_area:'.$state.'|country:'.$country;
  $url = "https://maps.googleapis.com/maps/api/geocode/json?".implode('&',$query_params).'&key=AIzaSyDsgeBIjdh92uqzr0jWMHz_2YRljj_4sxc';
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
  curl_setopt($curl, CURLOPT_HEADER, 0);
  $result = curl_exec($curl);
  curl_close($curl);
  return $result;
}

?>