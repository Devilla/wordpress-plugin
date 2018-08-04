<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://useinfluence.co
 * @since             1.0.0
 * @package           Influence
 *
 * @wordpress-plugin
 * Plugin Name:       Influence
 * Plugin URI:        https://github.com/InfluenceIO/wordpress-plugin
 * Description:       Influence WordPress Plugin for TrackingId Input.
 * Version:           1.0.0
 * Author:            Target Solutions
 * Author URI:        https://useinfluence.co
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       useinfluence
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-useinfluence-activator.php
 */
function activate_useinfluence() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-useinfluence-activator.php';
	Useinfluence_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-useinfluence-deactivator.php
 */
function deactivate_useinfluence() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-useinfluence-deactivator.php';
	Useinfluence_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_useinfluence' );
register_deactivation_hook( __FILE__, 'deactivate_useinfluence' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-useinfluence.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

function run_useinfluence() {

	$plugin = new Useinfluence();
	$plugin->run();

}

/**
 * The hook action to register plugin menu  method.
 */
add_action('admin_menu', 'basic_plugin_menu');

/**
 * The core plugin menu  method that is used to define app name app id etc,
 * admin-control and public-facing.
 */

function basic_plugin_menu(){
  $appName = 'UseInfluence';
  $appID = 'influence-plugin';
  add_menu_page($appName, $appName, 'administrator', $appID . '-top-level', 'plugin_admin_screen');
}

/**
 * The core pluginAdminScreen method that is used to define trackingId as input for app,
 */


function plugin_admin_screen() {
	echo "<a href='https://useinfluence.co'>";
	echo "<img class='top-logo' src='https://useinfluence.co/static/media/logo-influence-2.a5936714.png' width='180px' height='50px' style='margin-top:20px;' >";
	echo "</a>";
	echo "<br />";
	echo "<h3 class='describe' style='font-family:sans-serif;padding: 10px;border-left:  5px solid  #999;background: #99999930;'>If you don't have an account -";
	echo "<a href='https://useinfluence.co/signup'>";
	echo "<strong>signup here!</strong>";
	echo "</a>";
	echo "</h3>";
	echo "<h2>Please enter your Tracking ID</h2>";
	echo "<form action='' method='POST'>";
  echo "<input type='text' name='trackingId' class='api' style='padding: 5px 10px; border-radius:5px;' placeholder='e.g. INF-xxxxxxxx'></input>";
	echo "<br /> <hr />";
	echo "<input type='submit' class='submit' style='padding: 5px 10px ;cursor:pointer; color:#fff; border-radius:5px;background-color:#097fff' value='Save'></input>";
	echo "<form>";

 	 global $trackingId;
	 global $wpdb;

	 $query = $wpdb->get_results("SELECT trackingId FROM tracking_id", OBJECT);
	 foreach($query as $row)
	 {
				 $trackingId = $row->trackingId;
	 }

	 if($_POST["trackingId"]!=''){
			$trackingId = $_POST["trackingId"];
	  	$date = date("Y-m-d h:i:s");
			}

	/**
	 * WordPress database queries
		*/



	$sql1 = "CREATE TABLE tracking_id (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	  trackingId varchar(20) NOT NULL,
	  PRIMARY KEY  (id)
	)";

	$wpdb->query($sql1);

	$sql3 ="INSERT INTO  tracking_id(time, trackingId ) VALUES ('$date', '$trackingId')";
	$wpdb->query($sql3);

	$sql2 = "SELECT trackingId FROM tracking_id where trackingId='$trackingId'";
	echo $wpdb->query($sql2);
}

add_action('wp_head', 'add_influence');

/**
 * The script tag header paste method which retreives user trakingId from database and pass to script,
 */

function add_influence(){
	global $wpdb;
	$query = $wpdb->get_results("SELECT trackingId FROM tracking_id", OBJECT);
	foreach($query as $row)
	{
				$trackingId = $row->trackingId;
	}
				echo "
				<script src='https://storage.googleapis.com/influence-197607.appspot.com/influence-analytics.js'> </script>
				<script>
				new Influence({
				trackingId: '$trackingId'
				});
				</script>
						 ";
	}
};

run_useinfluence();

  ?>
