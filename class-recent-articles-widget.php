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
