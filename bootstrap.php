<?php
/**
 * @package Recent_Articles
 * @version 0.1.0
 * @since
 * @author
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 */

/*
Plugin Name: Recent Articles
Description: Display recent articles at the bottom of every article
Version: 0.1.0
Author: Hoi Kit Yuen
Author URI: https://www.linkedin.com/in/hoi-yuen-08980775/
*/

defined( 'ABSPATH' ) or die( 'Direct access not allowed');


define( 'RA__MINIMUM_WP_VERSION', '4.4' );
define( 'RA__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( 'class-recent-articles.php' );

register_activation_hook( __FILE__, array( 'Recent_Articles', 'onActivation' ) );
register_deactivation_hook( __FILE__, array( 'Recent_Articles', 'onDeactivation') );
register_uninstall_hook( __FILE__, array( 'Recent_Articles', 'onUninstall') );

// if ( is_admin() ) {
// }
// not belong here.
add_action( 'widgets_init', function() {
	require_once( 'class-recent-articles-widget.php' );
	register_widget( 'Recent_Articles_Widget' );
});

