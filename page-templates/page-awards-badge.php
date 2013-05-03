<?php 
/*
Template Name: Award Badge
*/
//send iframe award
if (isset($_REQUEST['award'])) {
	$city = $_REQUEST['award'];
	$cityUpper=str_replace('-', ' ', strtoupper($city));
	$city=strtolower($city);
	?>
	<div style="width:285px; height: 80px; margin: 5px;background-image:url(<?php bloginfo('template_directory') ?>/images/window-cleaning.png);">
		<a href="<?php echo network_site_url().$city; ?>" target="_blank" style="text-decoration:none;"><h3 style="padding: 10px 0px 0px 50px; margin: 0px; color: #ffffff;font: normal 15px Arial, sans-serif;"><?php echo $cityUpper; ?></h3><h2 style="margin: 0px; padding: 0px 0px 0px 50px; color: #ffffff; font: bold 15px Arial, sans-serif;">2013 BEST WINDOW CLEANING</h2></a>
		<a href="http://www.windowcleaning.com" target="_blank"><img style="padding: 0px 0px 0px 47px;"src="<?php echo get_template_directory_uri(); ?>/images/best-window-cleaners.png" alt="" />
		</a>
	</div>
	<?php
}
?>