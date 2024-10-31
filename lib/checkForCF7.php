<?php

/*
*  bd_error() Looks to see if CF7 is installed and activated. if not it returns error.
*/
function bdOA_error() {

    if( !file_exists(WP_PLUGIN_DIR.'/contact-form-7/wp-contact-form-7.php') ) {
      $error  = '<div class="error" id="message"><p>Contact Form 7 must be installed before contact-form-7-boberdoo-extension will work.</p><br/>';
      $error .= '<p>Download it <a href="' . admin_url('plugin-install.php?tab=plugin-information&plugin=contact-form-7&from=plugins') . '">here</a></p></div>';
      echo $error;
    } else if ( !class_exists( 'WPCF7') ) {
      $error = '<div class="error" id="messages"><p>Contact Form 7 Must be Activated before contact-form-7-boberdoo-extension will work.</p></div>';
      echo $error;
    }
}
add_action('admin_notices', 'bdOA_error');

