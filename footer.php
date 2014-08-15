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
<!-- Lead Modal -->
<div class="big-modal">
  <div class="modal hide fade bg-slate" id="member-application">
    <div class="modal-header center bumper-top bumper-bottom">
     <!-- <h3><strong class="green">100% refund</strong> within 60 days if you don’t love being a member.</h3>-->
      <h3>Join <strong class="green">North America's Best</strong> Window Cleaners.</h3>
    </div>
    <div class="modal-body center bumper-top">
      <div>
        <div class="input-prepend">
          <span class="add-on"><i class="icon-user"></i></span>
          <input class="member-name" validation="not-empty min-length-3" data-field-name="Name" placeholder="Your Name" type="text" value="" />
        </div>
      </div>
      <div>
        <div class="input-prepend">
          <span class="add-on"><i class="icon-briefcase"></i></span>
          <input class="member-company" validation="not-empty min-length-3" placeholder="Company Name" type="text" value="" />
        </div>
      </div>
      <div>
        <div class="input-prepend">
          <span class="add-on"><i class="icon-map-marker"></i></span>
          <div class="btn-group select-state-province">
            <a class="btn dropdown-toggle btn-medium" data-toggle="dropdown" href="#">State / Province <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li class="list-description">US States</li>
              <?php
              $states = tb_get_us_states();
              foreach($states as $state){
              ?>
              <li><a href="" data-state-province="United States, <?=$state?>"><?=$state?></a></li>
              <?php } ?>
              <li class="list-description">Canada Provinces</li>
              <?php
              $provinces = tb_get_ca_provinces();
              foreach($provinces as $province){
                ?>
                  <li><a href="" data-state-province="Canada, <?=$province?>"><?=$province?></a></li>
                <?php
              }
              ?>
            </ul>
          </div>
        </div>
      </div>
      <div>
        <div class="input-prepend">
          <span class="add-on"><i class="icon-map-marker"></i></span>
          <input class="member-city" validation="not-empty" placeholder="City" type="text" value="" />
        </div>
      </div>
      <div>
        <div class="input-prepend">
          <span class="add-on"><i class="icon-envelope"></i></span>
          <input class="member-email lowercase-only" validation="not-empty email" placeholder="Email Address" type="email" value="" />
        </div>
      </div>
      <div>
        <div class="input-prepend">
          <span class="add-on"><i class="icon-phone-halfling"></i></span>
          <input class="member-phone" validation="not-empty phone" placeholder="Phone Number" type="tel" value="" />
        </div>
      </div>
     <!-- <div class="bumper-top-small bumper-bottom">
        <div class="pen-stroke"></div>
      </div>-->
      <!--<p class="page-left page-right">Join North America's Best Window Cleaners</p>-->
    </div>

    <div class="modal-footer center">
      <a href="" class="btn btn-large btn-success save" data-nonce="<?=wp_create_nonce('new-membership-'.date('Ymd'))?>">Take a walk through</a>
    </div>
  </div>
<!-- / modal -->
<?php wp_footer(); ?>
</body>
</html>