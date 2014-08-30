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

<section class="container gentle-shadow bg-white">
	<div class="center">
		<div class="row-fluid">
      <div class="select-location">
        <?php if(isset($_REQUEST['find'])){ ?>
        <h4><strong>Looks like we don't service that area yet, try searching below for a member in your area</strong></h4>
        <?php } ?>
        <h2>I need a great window cleaner in... </h2>
        <div class="buttons">
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
          <!--button class="btn btn-large btn-success lookup" data-nonce="<?=wp_create_nonce('select-location-'.date('Ymd'))?>">Go!</button-->
          <p class="muted"><small>Recommended by customers in over 900 cities.</small></p>
        </div>
      </div>
      <div class="city-list-container">
        <ul class="city-list">
          <li>
            <span class="text center">Please select a location</span>
          </li>
          <!--li>
            <a href="http://www.windowcleaning.com/atlanta/" target="_blank"><span class="city">Atlanta,</span> <span class="state">South Carolina</span><span class="company-name">Crystal Clean Window Cleaning</span><span class="arrow"></span></a>
          </li-->
        </ul>
      </div>
      <div id="google-maps"></div>
	</div>
	</div>
</section>
<?php get_footer(); ?>
