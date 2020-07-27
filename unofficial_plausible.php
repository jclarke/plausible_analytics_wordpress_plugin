<?php
/**
 * @package Unofficial Plausible Analytics Plugin
 * @version 1.0.0
 */
/*
Plugin Name:  Unofficial Plausible Analytics Plugin
Plugin URI: https://blog.taskbill.io/plausible-analytics-plugin/
Description: This plugin adds the Plausible Analytics script to your wordpress site and can optionally not send analytics data for logged in wordpress admin users.
Author: TaskBill.io
Version: 1.0.0
Author URI: http://taskbill.io
*/


add_action( 'wp_head', 'plausible_unofficial_add_snippet');


function plausible_unofficial_add_snippet() {
	$host = get_option("plausible_unofficial_domain_name");
	$disabled_admin_tracking =  get_option("plausible_unofficial_disable_admin_analytics");
	
	if ( ($disabled_admin_tracking && !current_user_can( 'administrator' ) ) ||  !$disabled_admin_tracking ) {
  	  echo "<script async defer data-domain='" . $host . "' src='https://plausible.io/js/plausible.js'></script>";	
	}
}



// Add an option to save the custom url
function  plausible_unofficial_register_settings() {
   $default_host = parse_url(site_url())["host"];
   add_option( 'plausible_unofficial_domain_name',$default_host );
   add_option( 'plausible_unofficial_disable_admin_analytics');
      
   register_setting( 'plausible_unofficial_options_group', 'plausible_unofficial_domain_name' );
   register_setting( 'plausible_unofficial_options_group', 'plausible_unofficial_disable_admin_analytics' );

}
add_action( 'admin_init', 'plausible_unofficial_register_settings' );

// Add the option to the setting in Wordpress
function plausible_unofficial_register_options_page() {
  add_options_page('Plausible Analytics Unofficial Plugin', 'Plausible Analytics Settings', 'manage_options', 'plausible_unofficial', 'plausible_unofficial_options_page');
}
add_action('admin_menu', 'plausible_unofficial_register_options_page');

function plausible_unofficial_options_page(){
?>
  <div>
  <?php screen_icon(); ?>
  <h2>Unofficial Plausible Analytics Plugin by <a href="https://TaskBill.io/?utm_source=plausible_plugin">TaskBill.io</a></h2>
  <p>Please verify your domain matches the domain you have entered in Plausible Analytics. <br>You can also disable tracking of any user that is logged in as an administrator by checking the Disable Tracking of Admin Users below.</p>
  
  <form method="post" action="options.php">
  <?php settings_fields( 'plausible_unofficial_options_group' ); ?>
  <table>
  <tr valign="top">
  <th scope="row"><label for="plausible_unofficial_domain_name">Domain</label></th>
  <td><input type="text" id="plausible_unofficial_domain_name" name="plausible_unofficial_domain_name" value="<?php echo get_option("plausible_unofficial_domain_name"); ?>" /></td>
  </tr>
  <tr valign="top">
  <th scope="row"><label for="plausible_unofficial_disable_admin_analytics">Disable Tracking of Admin Users</label></th>
  <td><input type="checkbox" id="plausible_unofficial_disable_admin_analytics" name="plausible_unofficial_disable_admin_analytics" value='1'  <?php echo checked(1, get_option("plausible_unofficial_disable_admin_analytics"),false); ?> />  
  </td>
  </tr>
  </table>
  <?php  submit_button(); ?>
  </form>
  </div>
<?php
	}