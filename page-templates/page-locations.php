<?php
/**
 * Template Name: WC Locations
 * Description: List of wc.com locations
 *
 * @package 
 * @subpackage 
 * @since 
 */
 
get_header();
?>

<section class="container gentle-shadow bg-white bumper-bottom-medium">
	<div class="page-left page-right bumper-top-medium center">
		<div class="row-fluid">
			<div class="offset1 span10">
				<div class="select-location">
          <?php if(isset($_REQUEST['find'])){ ?>
					<h4><strong>Looks like we don't service that area yet, try searching below for a member in your area</strong></h4><br />
          <?php } ?>
					<h2>I need a great window cleaner in... </h2>
					<span class="btn-group select-country">
            <a class="btn dropdown-toggle btn-large" data-toggle="dropdown" href="" data-country="US">United States <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="" data-country="CA">Canada</a></li>
            </ul>
					</span>
					<span class="btn-group select-state-province">
						<a class="btn dropdown-toggle btn-large" data-toggle="dropdown" href="#">State <span class="caret"></span></a>
						<ul class="dropdown-menu" style="max-height:400px;overflow:auto;">
              <?php
                $states = tb_get_us_states();
                foreach($states as $abr=>$state){
              ?>
							<li><a href="" data-state-province="<?=$abr?>"><?=$state?></a></li>
              <?php } ?>
						</ul>
					</span>
          <div id="state-province-templates" class="hide">
            <?php
              $provinces = tb_get_ca_provinces();
              foreach($provinces as $abr=>$province){
            ?>
					  <li><a href="" data-state-province="<?=$abr?>"><?=$province?></a></li>
            <?php
              }
            ?>
          </div>
					<button class="btn btn-large btn-success lookup" data-nonce="<?=wp_create_nonce('select-location-'.date('Ymd'))?>">Go!</button>
					<p class="muted"><small>Recommended by customers in over 900 cities.</small></p>
				</div>
				<ul class="city-list"></ul>
			</div>
	</div>
	</div>
</section>




<?php 
the_post();
the_content();
get_footer(); 
?>
