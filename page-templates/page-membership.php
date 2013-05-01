<?php
/**
 * Template Name: Membership
 * Description: List of wc.com locations
 *
 * @package 
 * @subpackage 
 * @since 
 */
 
get_header();
the_post();
the_content();
?>

<!-- Lead Modal -->
<div class="big-modal">
  <div class="modal hide fade bg-slate" id="member-application">
    <div class="modal-header center bumper-top bumper-bottom">
      <h3><strong class="green">100% refund</strong> within 60 days if you don’t love being a member.</h3>
    </div>
    <div class="modal-body center bumper-top">
      <div>
        <div class="input-prepend">
            <span class="add-on"><i class="icon-user"></i></span>
            <input validation="not-empty" data-field-name="Name" placeholder="Your Name" type="text" value="" />
        </div>
      </div>
      <div>
        <div class="input-prepend">
            <span class="add-on"><i class="icon-breifcase"></i></span>
            <input validation="not-empty" placeholder="Company Name" type="text" value="" />
        </div>
      </div>
      <div>
        <div class="input-prepend">
            <span class="add-on"><i class="icon-map-marker"></i></span>
            <input validation="not-empty" placeholder="City" type="text" value="" />
        </div>
      </div>
      <div>
        <div class="input-prepend">
            <span class="add-on"><i class="icon-envelope"></i></span>
            <input validation="not-empty email" placeholder="Email Address" type="text" value="" />
        </div>
      </div>
      <div>
        <div class="input-prepend">
            <span class="add-on"><i class="icon-phone-halfling"></i></span>
            <input  validation="not-empty phone" placeholder="Phone Number" type="text" value="" />
        </div>
      </div>
      <div class="bumper-top-small bumper-bottom">
        <div class="pen-stroke"></div>
      </div>
      <p class="page-left page-right">One of our team members will contact you right away.</p>


    </div>

    <div class="modal-footer center">
      <a href="" class="btn btn-large btn-success save">Apply now</a>
    </div>
  </div>
<!-- / modal -->

<?php 
get_footer(); 
?>
