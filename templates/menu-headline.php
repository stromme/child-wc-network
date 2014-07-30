<?php
/**
 * Template_Name: Headline
 * Description: Fixed to top page headline and call to action
 *
 * @package 
 * @subpackage 
 * @since 
 */


if ( get_bloginfo('name') == 'Membership') {
	// Get the membership page being viewed and put it in the H1 element.
	$title = ucwords((isset($post->post_title))?$post->post_title:'');
?>
	<header class="container">
		<div class="headline headline-small">
			<ul>
				<li class="headline-title">
					<h1 itemprop="description"><?=$title?></h1>
				</li>
				<li class="headline-phone">
					<h2 class="white" ><a href="#" class="link-inverse link-decorate link-showoff member-apply">Learn More</a></h2>
				</li>
			</ul>
			<nav class="pull-right header-cta header-cta-small">
				
				<div class="center border-radius header-cta-well">
					<div class="hidden-desktop center"><a href="<?=get_home_url()?>"><img src="<?php echo TOOLBOX_IMAGES; ?>/wc-logo-simplified.png" itemprop="logo"></a></div>
					<div class="pen-stroke hidden-desktop"></div>
					<h3>We only approve <strong>one member</strong> from each city.</h3>
					<!--<button class="btn btn-success">See if you qualify</button>-->
				</div>
				
			</nav>
			
		</div>
		
		<!-- Brand -->
		<div class="brand visible-desktop" itemprop="brand" itemscope="http://schema.org/Brand">
			<a href="<?=get_home_url()?>"><img src="<?php echo get_header_image(); ?>" itemprop="logo"></a>
      <span class="hide" itemprop="brand">Windowcleaning.com</span>
		</div>
		<!-- /Brand -->
		
	</header>

<?php 
} else {

	global $post;
	$seo = get_location_seo();
	
	// Get the page title
	
	if ( 'cftl-tax-landing' == get_post_type() ) {
		$title = 'North America\'s <b> Best '.ucwords($post->post_title).'</b>';
	} elseif ( is_front_page() ) {
		$title = 'North America\'s <b> Best Window Cleaning</b> Professionals.';
	} else {
		$title = ucwords((isset($post->post_title))?$post->post_title:'');
	}
	
	
	?>
	
	
	<header class="container">
		<div class="headline">
			<ul>
				<li class="headline-title">
					<h1 itemprop="description"><?=$title?></h1>
				</li>
				<li class="headline-phone">
					<?php $tb_company = get_option('tb_company'); ?>
				
					<h2 class="white" ><a href="<?=get_home_url()."/locations/"?>" class="link-inverse link-decorate link-showoff" data-toggle="tooltip" data-placement="bottom" title="Find your nearest location">Over 900 Locations</a></h2>
				</li>
			</ul>
			<nav class="pull-right header-cta">
				
				<div class="center border-radius header-cta-well">
					<div class="hidden-desktop center"><a href="<?=get_home_url()?>"><img src="<?php echo TOOLBOX_IMAGES; ?>/wc-logo-simplified.png" itemprop="logo"></a></div>
					<div class="pen-stroke hidden-desktop"></div>
					<h3>Find your nearest location and <strong>get an instant quote.</strong></h3>
					<form class="form-search" method="post" action="">
					  <div class="input-append find-location">
					    <input type="text" class="search-query jumbo-input input-medium" validation="not-empty zip" placeholder="ZIP or Postal Code">
					    <button type="submit" class="btn btn-success jumbo-input" data-nonce="<?=wp_create_nonce('find-location-'.date('Ymd'))?>">Go!</button>
					  </div>
					</form>

				</div>
				
			</nav>
			
		</div>
		
		<!-- Brand -->
		<div class="brand visible-desktop" itemprop="brand" itemscope="http://schema.org/Brand">
      <?php
      $header = get_header_image();
      $ss = get_stylesheet_directory_uri();
      if(strstr($header, $ss)){
        $ss_file = str_replace($ss, get_stylesheet_directory(), $header);
        if(file_exists($ss_file)) $header = get_header_image();
        else {
          $ss_file = str_replace($ss, get_template_directory(), $header);
          if(file_exists($ss_file)) $header = str_replace($ss, get_template_directory_uri(), $header);
          else $header = THEME_IMAGES."brand/window-cleaning-dot-com-logo.png";
        }
      }
      ?>
			<a href="<?=get_home_url()?>"><img src="<?=$header?>" itemprop="logo"></a>
		</div>
		<!-- /Brand -->
		
	</header>
	
<?php 
}
?>
