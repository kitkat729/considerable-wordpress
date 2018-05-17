<?php
/**
 * @package Recent_Articles
 * @subpackage Views
 */
?>
<div>
	<p>
		<?php
			$title = !empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New Title', 'recent-articles' );
		?>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ) ?>">
			<?php _e( 'Title:', 'recent-articles') ?>
		</label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ) ?>"
		name="<?php echo $this->get_field_name( 'title' ) ?>" value="<?php echo esc_attr( $title ) ?>">
	</p>
	<p>
		<?php
			$limit = !empty( $instance['limit']) ? intval( $instance['limit'] ) : 5;
		?>
		<label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ) ?>">
			<?php _e( 'Limit', 'recent-articles' ) ?>
		</label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'limit' ) ?>"
		name="<?php echo $this->get_field_name( 'limit' ) ?>" value="<?php echo esc_attr( $limit ) ?>">
	</p>
</div>
