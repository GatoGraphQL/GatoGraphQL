<?php
/**
 * @author marcus
 * Standard events list widget
 */
class EM_Locations_Widget extends WP_Widget {
	
	var $defaults = array();
	
    /** constructor */
    function __construct() {
    	$this->defaults = array(
    		'title' => __('Event Locations','events-manager'),
    		'scope' => 'future',
    		'order' => 'ASC',
    		'limit' => 5,
    		'format' => '<li>#_LOCATIONLINK<ul><li>#_LOCATIONADDRESS</li><li>#_LOCATIONTOWN</li></ul></li>',
    	    'no_locations_text' => '<li>'.__('No locations', 'events-manager').'</li>',
    		'orderby' => 'event_start_date,event_start_time,location_name'
    	);
    	$this->em_orderby_options = array(
    		'event_start_date, event_start_time, location_name' => __('Event start date/time, location name','events-manager'),
    		'location_name' => __('Location name','events-manager')
    	);
    	$widget_ops = array('description' => __( "Display a list of event locations on Events Manager.", 'events-manager') );
        parent::__construct(false, $name = 'Event Locations', $widget_ops);	
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
    	
	    //make sure no owner searches are being run
		$instance['owner'] = false;
		//legacy sanitization
		if( !preg_match('/^<li/i', trim($instance['format'])) ) $instance['format'] = '<li>'. $instance['format'] .'</li>';
		//get locations
		$locations = EM_Locations::get(apply_filters('em_widget_locations_get_args',$instance));
		//output locations
		echo "<ul>";
		if ( count($locations) > 0 ){
			foreach($locations as $location){
				echo $location->output($instance['format']);
			}
		}else{
		    echo $instance['no_locations_text'];
		}
		echo "</ul>";               
		
	    echo $args['after_widget'];
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
    	//filter the new instance and replace blanks with defaults
    	foreach($this->defaults as $key => $value){
    		if( !isset($new_instance[$key]) ){
    			$new_instance[$key] = $value;
    		}
    		//make sure formats and the no locations text are wrapped with li tags
		    if( ($key == 'format' || $key == 'no_locations_text') && !preg_match('/^<li/i', trim($new_instance[$key])) ){
            	$new_instance[$key] = '<li>'.$new_instance[$key].'</li>';
		    }
		    //balance tags and sanitize output formats
		    if( in_array($key, array('format', 'no_locations_text')) ){
		        $new_instance[$key] = force_balance_tags(wp_kses_post($new_instance[$key]));
		    }
    	}
    	return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
    	$instance = array_merge($this->defaults, $instance);
        ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title', 'events-manager'); ?>: </label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('limit'); ?>"><?php esc_html_e('Show number of locations','events-manager'); ?>: </label>
			<input type="text" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" value="<?php echo esc_attr($instance['limit']); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('scope'); ?>"><?php esc_html_e('Scope of the locations','events-manager'); ?>:</label><br/>
			<select class="widefat" id="<?php echo $this->get_field_id('scope'); ?>" name="<?php echo $this->get_field_name('scope'); ?>" >
				<?php foreach( em_get_scopes() as $key => $value) : ?>   
				<option value='<?php echo esc_attr($key) ?>' <?php echo ($key == $instance['scope']) ? "selected='selected'" : ''; ?>>
					<?php echo esc_html($value); ?>
				</option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('order'); ?>"><?php esc_html_e('Order By','events-manager'); ?>: </label>
			<select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>">
				<?php foreach($this->em_orderby_options as $key => $value) : ?>   
	 			<option value='<?php echo esc_attr($key); ?>' <?php echo ( !empty($instance['orderby']) && $key == $instance['orderby']) ? "selected='selected'" : ''; ?>>
	 				<?php echo esc_html($value); ?>
	 			</option>
				<?php endforeach; ?>
			</select> 
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('order'); ?>"><?php esc_html_e('Order of the locations','events-manager'); ?>:</label><br/>
			<select class="widefat" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" >
				<option value="ASC" <?php echo ($instance['order'] == 'ASC') ? 'selected="selected"':''; ?>><?php esc_html_e('Ascending','events-manager'); ?></option>
				<option value="DESC" <?php echo ($instance['order'] == 'DESC') ? 'selected="selected"':''; ?>><?php esc_html_e('Descending','events-manager'); ?></option>
			</select>
		</p>
		<em><?php echo sprintf( esc_html__('The list is wrapped in a %s tag, so if an %s tag is not wrapping the formats below it will be added automatically.','events-manager'), '<code>&lt;ul&gt;</code>', '<code>&lt;li&gt;</code>'); ?></em>
		<p>
			<label for="<?php echo $this->get_field_id('format'); ?>"><?php esc_html_e('List item format','events-manager'); ?>: </label>
			<textarea rows="10" cols="20" class="widefat" id="<?php echo $this->get_field_id('format'); ?>" name="<?php echo $this->get_field_name('format'); ?>"><?php echo esc_textarea($instance['format']); ?></textarea>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('no_locations_text'); ?>"><?php esc_html_e('No Locations message','events-manager'); ?>: </label>
			<input type="text" id="<?php echo $this->get_field_id('no_locations_text'); ?>" name="<?php echo $this->get_field_name('no_locations_text'); ?>" value="<?php echo esc_attr( $instance['no_locations_text'] ); ?>" >
		</p>
        <?php 
    }
}
add_action('widgets_init', create_function('', 'return register_widget("EM_Locations_Widget");'));
?>