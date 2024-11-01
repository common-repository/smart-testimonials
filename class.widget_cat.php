<?php
	
	class multi_testimonial_widgets_cat extends WP_Widget {

	public function __construct() {
		// widget actual processes
		
		parent::__construct(
			'smart_testimonial_widget', // Base ID
			'Smart Testimonail Widget', // Name
			array( 'description' => __( 'Smart Testimonail Widget with multiple options', 'text_domain' ), ) // Args
		);
	}
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		// Two variables are coming in INSTANCE ARRAY , title and limit
		
		$title = apply_filters( 'widget_title', $instance['title'] );
		$limit = $instance['limit'];
		$displayimg = $instance['displayimg'];
		$custcss = $instance['custcss'];
		
		$atts['cat_name'] = $title;
		$atts['limit'] = $limit;
		$atts['displayimg'] = $displayimg;
		$atts['custcss'] = $custcss;
		echo $args['before_widget'].'<h2>'.$title.'</h2><br>';
		if ( ! empty( $title ) ){
		// Pre define short function is used for widget, for customization please see shortcodes file
		
		list_asptesti_shortcode($atts);
		}
		echo $args['after_widget'];
		
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	
 	public function form( $instance ) {
		// outputs the options form on admin
		
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( '', 'text_domain' );
		}
		
		if ( isset( $instance[ 'limit' ] ) ) {
			$limit = $instance[ 'limit' ];
		}
		
		if ( isset( $instance[ 'displayimg' ] ) ) {
			$displayimg = $instance[ 'displayimg' ];
		}
		
		if ( isset( $instance[ 'custcss' ] ) ) {
			$custcss = $instance[ 'custcss' ];
		}
		
		// Printing Form for backend with two fields SLUD and LIMIT
		?>
		<p>
		<label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<p>
		<label >Testimonails IDs</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="text" value="<?php echo esc_attr( $limit ); ?>" />
		Enter comma seperated multiple IDs OR single ID.
		</p>
		
		
		<p>
		<label >Display Images</label><br />
		
		<input type="radio" name="<?php echo $this->get_field_name( 'displayimg' ); ?>" value="YES" <?php if($displayimg == 'YES'){echo 'checked="checked"';}?> />YES<br>
		<input type="radio" name="<?php echo $this->get_field_name( 'displayimg' ); ?>" value="NO" <?php if($displayimg == 'NO'){echo 'checked="checked"';}?> />NO
		
		</p>
		
		<p>
		<label >Add Custom CSS</label><br />
		<textarea name="<?php echo $this->get_field_name( 'custcss' ); ?>" rows="6" style="width:100%;"><?php echo esc_attr( $custcss ); ?></textarea>
		
		</p>
		
		<?php 
		
	}
	
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	

	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		// Updating two variables of INSTANCE ARRAY, title and limit
		
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['limit'] = ( ! empty( $new_instance['limit'] ) ) ? strip_tags( $new_instance['limit'] ) : '';
		$instance['displayimg'] = ( ! empty( $new_instance['displayimg'] ) ) ? strip_tags( $new_instance['displayimg'] ) : '';
		$instance['custcss'] = ( ! empty( $new_instance['custcss'] ) ) ? strip_tags( $new_instance['custcss'] ) : '';

		return $instance;
	}
}
	
?>