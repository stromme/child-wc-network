<?php
/**
 * Template_Name: Headline
 * Description: Fixed to top page headline and call to action
 *
 * @package 
 * @subpackage 
 * @since 
 */

$seo = get_location_seo();
?>

<!-- Headline - Fixed to top of page
==================================================
-->

<header class="container">
	<div class="headline headline-1200">
		<ul>
			<li class="headline-title">
				<h1 itemprop="description">North America's <b>Best Window Cleaning Professionals. </b></h1>
			</li>
			<li class="headline-phone">
				<?php $tb_company = get_option('tb_company'); ?>
			
				<h2 class="white" ><a href="<?=get_home_url()."locations/"?>" class="link-inverse link-decorate link-showoff" data-toggle="tooltip" data-placement="bottom" title="Find your nearest location">Over 900 Locations</a></h2>
			</li>
		</ul>
		<nav class="pull-right header-cta">
			
			<div class="center border-radius header-cta-well">
				<div class="hidden-desktop center"><a href="<?=get_home_url().$blog_prefix."/"?>"><img src="<?php echo TOOLBOX_IMAGES; ?>/wc-logo-simplified.png" itemprop="logo"></a></div>
				<div class="pen-stroke hidden-desktop"></div>
				<h3>Find your nearest location and <strong>get a instant quote.</strong></h3>
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
