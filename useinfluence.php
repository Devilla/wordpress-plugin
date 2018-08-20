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
define( 'INFLUENCE_PLUGIN_VERSION', '1.0.0' );

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
add_action('admin_menu', 'influence_menu');

/**
 * The core plugin menu  method that is used to define app name app id etc,
 * admin-control and public-facing.
 */

function influence_menu(){
  $appName = 'Influence';
  $appID = 'influence-plugin';
  add_menu_page($appName, $appName, 'administrator', $appID . '-top-level', 'influence_screen');
}

/**
 * The core pluginAdminScreen method that is used to define trackingId as input for app,
 */


function influence_screen() {
	echo "<a href='https://useinfluence.co'>";
	echo '<img src="<?php echo plugin_dir_url(__FILE__) . 'assets/Influence-website-2.png' ?>" width="180px" height="50px" style="margin-top:20px;" >';
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
	echo "<br />";
	echo "<a href='https://useinfluence.co/campaigns/scripts' target='_blank'>Where is my Tracking ID ?</a>";
	echo "<form>";

 	 global $trackingId;
	 global $wpdb;

	 $query = $wpdb->get_results("SELECT trackingId FROM tracking_id ORDER BY ID DESC LIMIT 1", OBJECT);
	 foreach($query as $row)
	 {
				 $trackingId = $row->trackingId;
	 }

	 if(!preg_match("/INF-/", $trackingId)){
			$trackingId = '';
		}

	 if($_POST["trackingId"]!=''){
			$trackingId = $_POST["trackingId"];
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
	if($trackingId != ''){
		$sql3 ="INSERT INTO  tracking_id(trackingId) VALUES ('$trackingId')";
		$wpdb->query($sql3);
	}
}

add_action('wp_enqueue_scripts', 'add_influence');
add_action('wp_head', 'add_tracking_id');
/**
 * The script tag header paste method which retreives user trakingId from database and pass to script,
 */

function add_influence(){
	wp_enqueue_script( 'influence-script', 'https://storage.googleapis.com/influence-197607.appspot.com/influence-analytics.js', array(), '1.0.0', false );
}

function add_tracking_id(){
	global $trackingId;
	global $wpdb;
	$query = $wpdb->get_results("SELECT trackingId FROM tracking_id ORDER BY ID DESC LIMIT 1", OBJECT);
	foreach($query as $row)
	{
				$trackingId = $row->trackingId;
	}
	echo "
	<script>
	new Influence({
	trackingId: '$trackingId'
	});
	</script>
			 ";
	}

run_useinfluence();

  ?>
