<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * .container div element.
 *
 * @package Hatch
 * @subpackage 
 * @since 
 */
?>
	<section class="footer bg-slate-invert" itemprop="member" itemscope="http://schema.org/Organization">
		
		<h3>Find your nearest location and <strong>get a instant quote</strong></h3>
		<div class="form-search bumper">
		  <div class="input-append">
		    <input type="text" class="search-query jumbo-input input-medium" placeholder="ZIP or Postal Code">
		    <button type="submit" class="btn btn-success jumbo-input">Go!</button>
		  </div>
		</div>
		
		<img src="<?php echo THEME_IMAGES; ?>brand/window-cleaning-dot-com-logo.png" itemprop="logo" class="bumper-bottom-medium">
		
		<p class="footer-links">
			<a href="<?=get_home_url().$blog_prefix."/"?>home-window-cleaning">Home Window Cleaning</a>
			<a href="<?=get_home_url().$blog_prefix."/"?>commercial-window-cleaning">Commercial Window Cleaning</a>
			<a href="<?=get_home_url().$blog_prefix."/"?>locations" rel="nofollow">Our Locations</a>
			<a href="<?=get_home_url().$blog_prefix."/"?>news" rel="nofollow">News</a>
			<a href="<?=get_home_url().$blog_prefix."/"?>members">Apply for Membership</a></p>
		<p><small><span itemprop="description">North America's Best Window Cleaners</span> | Copyright 2013 | <a href="">Privacy</a> | <a href="">Terms</a></small></p>
	</section>

</div><!-- / .container -->
<? get_template_part('templates/modal', 'review'); ?>
<? get_template_part('templates/modal', 'lead'); ?>
<?php wp_footer(); ?>
</body>
</html>