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


define( 'RA_API_PREFIX', 'recent-articles' );
define( 'RA__MINIMUM_WP_VERSION', '4.4' );
define( 'RA__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( 'class-recent-articles.php' );

if ( is_admin() ) {
	register_activation_hook( __FILE__, array( 'Recent_Articles', 'onActivation' ) );
	register_deactivation_hook( __FILE__, array( 'Recent_Articles', 'onDeactivation') );
	register_uninstall_hook( __FILE__, array( 'Recent_Articles', 'onUninstall') );
} else {
	add_action( 'wp_enqueue_scripts', function() {
		wp_enqueue_style( 'recent-articles-global', plugins_url( 'assets/css/global.css', __FILE__ ) );

		wp_enqueue_script( 'momentjs', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.js');
		wp_enqueue_script( 'vuejs', 'https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js', array('momentjs') );
		wp_enqueue_script( 'recent-articles-global', plugins_url( 'assets/js/global.js', __FILE__ ), array('vuejs'), null, true );
		do_action( 'recent-articles_enqueue_scripts' );
	});
}

function recent_articles_widget_init () {
	require_once( 'class-recent-articles-widget.php' );
	register_widget( 'Recent_Articles_Widget' );
}
add_action( 'widgets_init', 'recent_articles_widget_init' );

function get_recent_articles(WP_REST_Request $request) {
	$data = array(
		'posts' => array()
	);
	$id = $request['id'];
	$limit = $request['limit'];

	$qry_options = array(
		'post_status' => 'publish',
		'orderby' => 'post_modified',
		'order' => 'DESC',
	);

	if ( !empty($id) ) {
		$qry_options['post__not_in'] = array( $id );
	}

	if ( !empty($limit) ) {
		$qry_options['posts_per_page'] = $limit;
		$qry_options['no_found_rows'] = true;
	}

	$qry = new \Wp_Query($qry_options);
	while ( $qry->have_posts() ) {
		$qry->the_post();

		$post = new stdClass;
		$post->id = get_the_ID();
		$post->title = get_the_title();
		$post->timestamp = get_the_modified_date( DateTime::ISO8601 );
		$post->permalink = get_permalink();

		$post->author = new stdClass;
		$post->author->id = get_the_author_meta( 'ID' );
		$post->author->url = get_the_author_meta( 'url' );
		$post->author->link = get_the_author_link();
		$post->author->displayName = get_the_author();

		$post->category = new stdClass;
		$term = get_the_category()[0];
		$post->category->id = $term->cat_ID;
		$post->category->name = $term->cat_name;

		$post->thumbnail = '';
		if ( has_post_thumbnail() ) {
			$post->thumbnail = get_the_post_thumbnail( get_the_ID() );
		}

		array_push($data['posts'], $post);
	}
	wp_reset_postdata();

	$data['totalCount'] = count($data['posts']);

	return $data;
}

add_action( 'rest_api_init', function() {
	register_rest_route( RA_API_PREFIX . '/v1', '/post/(?P<id>\d+)', array(
		'methods' => 'GET',
		'callback' => 'get_recent_articles',
	));
});

