<?php
/**
 * @author marcus
 * Standard events list widget
 */
class EM_Multiple_Bookings_Widget extends WP_Widget {

	var $defaults;

	/** constructor */
	function __construct() {
		$this->defaults = array(
				'title' => __('Event Bookings Cart','em-pro'),
				'format' => '#_EVENTLINK - #_EVENTDATES<ul><li>#_BOOKINGSPACES Spaces - #_BOOKINGPRICE</li></ul>',
				'loading_text' =>  __('Loading...','em-pro'),
				'checkout_text' => __('Checkout','em-pro'),
				'cart_text' => __('View Cart','em-pro'),
				'no_bookings_text' => __('No events booked yet','em-pro')
		);
		$widget_ops = array('description' => __( "Display a shopping cart widget for currently booked events to check out.", 'em-pro') );
		parent::WP_Widget(false, $name = 'Event Bookings Cart', $widget_ops);
	}

	/** @see WP_Widget::widget */
	function widget($args, $instance) {
		$instance = array_merge($this->defaults, $instance);
		echo $args['before_widget'];
		if( !empty($instance['title']) ){
			echo $args['before_title'];
		    echo apply_filters('widget_title',$instance['title'], $instance, $this->id_base);
			echo $args['after_title'];
		}
		echo EM_Multiple_Bookings::cart_contents($instance);
		echo $args['after_widget'];
	}

	/** @see WP_Widget::update */
	function update($new_instance, $old_instance) {
		foreach($this->defaults as $key => $value){
			if( !isset($new_instance[$key]) ){
				$new_instance[$key] = $value;
			}
		}
		return $new_instance;
	}

	/** @see WP_Widget::form */
	function form($instance) {
		$instance = array_merge($this->defaults, $instance);
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'em-pro'); ?>: </label>
			<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('format'); ?>"><?php _e('List item format','em-pro'); ?>: </label>
			<textarea rows="5" cols="24" id="<?php echo $this->get_field_id('format'); ?>" name="<?php echo $this->get_field_name('format'); ?>"><?php echo $instance['format']; ?></textarea>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('loading_text'); ?>"><?php _e('Loading text','em-pro'); ?>: </label>
			<input type="text" id="<?php echo $this->get_field_id('loading_text'); ?>" name="<?php echo $this->get_field_name('loading_text'); ?>" value="<?php echo (!empty($instance['loading_text'])) ? $instance['loading_text']:$this->defaults['loading_text']; ?>" >
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('no_bookings_text'); ?>"><?php _e('No bookings text','em-pro'); ?>: </label>
			<input type="text" id="<?php echo $this->get_field_id('no_bookings_text'); ?>" name="<?php echo $this->get_field_name('no_bookings_text'); ?>" value="<?php echo (!empty($instance['no_bookings_text'])) ? $instance['no_bookings_text']:$this->defaults['no_bookings_text']; ?>" >
		</p>
        <?php 
    }
}
add_action('widgets_init', create_function('', 'return register_widget("EM_Multiple_Bookings_Widget");'));