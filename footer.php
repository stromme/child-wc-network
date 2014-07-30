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
		
		<?php if ( ( !is_page('locations') ) && ( !is_page('members') ) )  { ?>
		<h3>Find your nearest location and <strong>get an instant quote.</strong></h3>
    <form class="form-search bumper" method="post" action="">
		  <div class="input-append find-location">
		    <input type="text" class="search-query jumbo-input input-medium" validation="not-empty zip" placeholder="ZIP or Postal Code">
		    <button type="submit" class="btn btn-success jumbo-input" data-nonce="<?=wp_create_nonce('find-location-'.date('Ymd'))?>">Go!</button>
		  </div>
		</form>
		<?php } ?>
		<img src="<?php echo THEME_IMAGES; ?>brand/window-cleaning-dot-com-logo.png" itemprop="logo" class="bumper-bottom-medium">
		
		<p class="footer-links">
			<a href="<?=get_home_url(1)?>/services/home-window-cleaning/">Home Window Cleaning</a>
			<a href="<?=get_home_url(1)?>/services/commercial-window-cleaning/">Commercial Window Cleaning</a>
			<a href="<?=get_home_url(1)?>/locations/" rel="nofollow">Our Locations</a>
			<a href="<?=get_home_url(1)?>/members/">Apply for Membership</a></p>
		<p><small><span itemprop="description">North America's Best Window Cleaners</span> | Copyright <?=date('Y')?> | <a href="<?=get_site_url(1)?>/privacy">Privacy</a> | <a href="<?=get_site_url(1)?>/terms">Terms</a></small></p>
	</section>

</div><!-- / .container -->
<? get_template_part('templates/modal', 'review'); ?>
<? get_template_part('templates/modal', 'lead'); ?>
<?php wp_footer(); ?>
</body>
</html>