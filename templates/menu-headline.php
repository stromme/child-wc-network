<?php
/**
 * Template_Name: Headline
 * Description: Fixed to top page headline and call to action
 *
 * @package 
 * @subpackage 
 * @since 
 */


if (is_page('membership')) {
	// Get the membership page being viewed and put it in the H1 element.
	$title = ucwords($post->post_title);	
?>
	<header class="container">
		<div class="headline headline-small">
			<ul>
				<li class="headline-title">
					<h1 itemprop="description"><?=$title?></h1>
				</li>
				<li class="headline-phone">
					<?php $tb_company = get_option('tb_company'); ?>
				
					<h2 class="white" ><a href="<?=get_home_url()."/membership/qualify/"?>" class="link-inverse link-decorate link-showoff" data-toggle="tooltip" data-placement="bottom" title="Find your nearest location">See If You Qualify</a></h2>
				</li>
			</ul>
			<nav class="pull-right header-cta header-cta-small">
				
				<div class="center border-radius header-cta-well">
					<div class="hidden-desktop center"><a href="<?=get_home_url().$blog_prefix."/"?>"><img src="<?php echo TOOLBOX_IMAGES; ?>/wc-logo-simplified.png" itemprop="logo"></a></div>
					<div class="pen-stroke hidden-desktop"></div>
					<h3>We only approve <strong>one member</strong> from each city.</h3>
					<!--<button class="btn btn-success">See if you qualify</button>-->
				</div>
				
			</nav>
			
		</div>
		
		<!-- Brand -->
		<div class="brand visible-desktop" itemprop="brand" itemscope="http://schema.org/Brand">
			<a href="<?=get_home_url().$blog_prefix."/"?>"><img src="<?php echo get_header_image(); ?>" itemprop="logo"></a>
		</div>
		<!-- /Brand -->
		
	</header>

<?php 
} else {

	global $post;
	$seo = get_location_seo();
	
	// Get the service type being viewed if we're on a services page and put it in the H1 element.
	$title = ( ( 'cftl-tax-landing' == get_post_type() ) ? 'North America\'s <b> Best '.ucwords($post->post_title).'</b>' : 'North America\'s <b> Best Window Cleaning</b> Professionals.' );
	
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
					<div class="hidden-desktop center"><a href="<?=get_home_url().$blog_prefix."/"?>"><img src="<?php echo TOOLBOX_IMAGES; ?>/wc-logo-simplified.png" itemprop="logo"></a></div>
					<div class="pen-stroke hidden-desktop"></div>
					<h3>Find your nearest location and <strong>get an instant quote.</strong></h3>
					<div class="form-search">
					  <div class="input-append">
					    <input type="text" class="search-query jumbo-input input-medium" placeholder="ZIP or Postal Code">
					    <button type="submit" class="btn btn-success jumbo-input">Go!</button>
					  </div>
					</div>
					
				</div>
				
			</nav>
			
		</div>
		
		<!-- Brand -->
		<div class="brand visible-desktop" itemprop="brand" itemscope="http://schema.org/Brand">
			<a href="<?=get_home_url().$blog_prefix."/"?>"><img src="<?php echo get_header_image(); ?>" itemprop="logo"></a>
		</div>
		<!-- /Brand -->
		
	</header>
	
<?php 
}
?>
