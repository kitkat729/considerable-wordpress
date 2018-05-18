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

		/*
			get caller's post type. If post type === 'post', add to query to exclude the current post

			wp get posts

				- published
				- order by date
				- limit N

		*/
		$limit = $instance['limit'];
		$exclude_the_post_itself = true;

		$qry_options = array(
			'post_status' => 'publish',
			'orderby' => 'post_modified',
			'order' => 'DESC',
		);

		if ( $exclude_the_post_itself ) {
			$qry_options['post__not_in'] = array( get_the_ID() );
		}

		if ( $limit ) {
			$qry_options['posts_per_page'] = $limit;
			$qry_options['no_found_rows'] = true;
		}

$posts = [];

		$qry = new \Wp_Query($qry_options);
		while ( $qry->have_posts() ) {
			$qry->the_post();

			// lets just build this as an object for now
			$post = new stdClass;
			$post->title = get_the_title();
			$post->date = get_the_date();
			$post->permalink = get_permalink();

			$post->author = new stdClass;
			$post->author->id = get_the_author_meta( 'ID' );
			$post->author->display_name = get_the_author();

			// @todo Get these too!
			// $post->taxonomy = get_the_terms();
			// $post->category

			// $post->media = array();
			// if ( $thumb_id = get_post_thumbnail_id() ) {
			// 	$media = new stdClass;
			// 	$media->type = 'thumbnail';
			// 	$media->url =
			// }


			array_push($posts, $post);
		}
		wp_reset_postdata();

		//$posts = var_export($qry->posts);
		//$posts = var_export($posts);

		if ( $posts ) {
			array_push($html, '<div>');
			array_push($html, print_r($posts, true));
			array_push($html, '</div>');
		}

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
