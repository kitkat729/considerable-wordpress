<?php
/**
 * @package Recent_Articles
 */
class Recent_Articles_Widget extends WP_Widget {

	protected $views;

	function __construct() {
		// @todo need to improve how the widget gets its form view
		$this->views = RA__PLUGIN_DIR .'views/';

		$widget_opts = array(
			'classname' => 'recent-articles-widget',
			// Widget caption
			'description' => __( 'Display recent articles')
		);
		parent::__construct(
			'recent_articles_widget',
			__( 'Recent Articles Widget', 'recent-articles'),
			$widget_opts
		);
	}

	/**
	 * Output widget HTML content
	 */
	public function widget( $args, $instance ) {
		$html = array();

		// widget wrapper
		array_push( $html, wp_kses_post( $args['before_widget']) );

		if ( !empty( $instance['title'] ) ) {
			array_push( $html, $args['before_title'] . apply_filters('widget_title', $instance['title'], $instance, $this->id_base) . $args['after_title'] );
		}

		$api_url = 'http://www-dev.bloggermon.com/wp-json/recent-articles/v1/post/'.get_the_ID();

		$query = array();
		if ( !empty($instance['limit']) ) {
			$query['limit'] = $instance['limit'];
		}

		$api_url .= ($query ? '?'.http_build_query($query, '', '&amp') : '');

$recent_posts = <<<HTML
<div id="recent-posts" apiUrl="$api_url">
	<!-- all bind variables must match up with component props -->
	<blog-post
		v-for="post in posts"
		v-bind:key="post.id"
		v-bind:title="post.title"
		v-bind:permalink="post.permalink"
		v-bind:thumbnail="post.thumbnail"
		v-bind:category="post.category"
		v-bind:author="post.author"
		v-bind:timestamp="post.timestamp"
	></blog-post>
</div>
HTML;

		array_push($html, $recent_posts);

		// widget wrapper
		array_push( $html, wp_kses_post($args['after_widget']) );

		echo implode( '', $html );
	}

	/**
	 * Output widget options form
	 */
	public function form( $instance ) {
		// @todo need to improve how widget options get initialized

		include( $this->views . 'form.php' );
	}

	/**
	 * Update widget options form
	 */
	public function update( $new_instance, $old_instance ){
		// @todo need to improve how widget options get initialized

		$instance = array();
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['limit'] = intval( $new_instance['limit'] );

		return $instance;
	}
}
