<?php
/*Plugin name : Influence Plugin
Version: 1.0
Description: WP plugin and TrackingId Input
Author: Devilla
Plugin URI: https://useinfluence.co/
*/

add_action('admin_menu', 'basicPluginMenu');

function basicPluginMenu(){
  $appName = 'Influence Plugin';
  $appID = 'basic-plugin';
  add_menu_page($appName, $appName, 'administrator', $appID . '-top-level', 'pluginAdminScreen');
}

function pluginAdminScreen() {
  echo "<h1>The Influence Plugin Admin Area</h1>";
  echo "<p>Please enter your TrakingID</p>";
  echo "<input type='text'></input>";
}

?>
