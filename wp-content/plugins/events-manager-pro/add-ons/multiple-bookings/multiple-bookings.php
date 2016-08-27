<?php
class EM_Multiple_Bookings{
    
    public static $booking_data;
    public static $session_started = false;
    
    public static function init(){
		include('multiple-booking.php');
		include('multiple-bookings-widget.php');
		if( is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX) ){ //admin stuff
		    //maybe for later use
		}elseif( !(!empty($_REQUEST['manual_booking']) && wp_verify_nonce($_REQUEST['manual_booking'], 'em_manual_booking_'.$_REQUEST['event_id'])) ){ //not admin area or a manual booking
			//modify traditional booking forms behaviour
			add_action('em_booking_form_custom','EM_Multiple_Bookings::prevent_user_fields', 1); //prevent user fields from showing
			add_filter('em_booking_validate', 'EM_Multiple_Bookings::prevent_user_validation', 1); //prevent user fields validation
	        //hooking into the booking process
	        add_action('em_booking_add','EM_Multiple_Bookings::em_booking_add', 1, 3); //prevent booking being made and add to cart
		}
		add_filter('em_booking_save','EM_Multiple_Bookings::em_booking_save',100,2); //when saving bookings, we need to make sure MB objects update the total price
		add_filter('em_get_booking','EM_Multiple_Bookings::em_get_booking'); //switch EM_Booking with EM_Multiple_Booking object if applicable
		add_filter('em_wp_localize_script', 'EM_Multiple_Bookings::em_wp_localize_script');
		//cart/checkout pages
		add_filter('the_content', 'EM_Multiple_Bookings::pages');
		//ajax calls for cart actions
		add_action('wp_ajax_emp_checkout_remove_item','EM_Multiple_Bookings::remove_booking');
		add_action('wp_ajax_nopriv_emp_checkout_remove_item','EM_Multiple_Bookings::remove_booking');
		add_action('wp_ajax_emp_empty_cart','EM_Multiple_Bookings::empty_cart_ajax');
		add_action('wp_ajax_nopriv_emp_empty_cart','EM_Multiple_Bookings::empty_cart_ajax');
		//ajax calls for cart checkout
		add_action('wp_ajax_emp_checkout','EM_Multiple_Bookings::checkout');
		add_action('wp_ajax_nopriv_emp_checkout','EM_Multiple_Bookings::checkout');
		//ajax calls for cart contents		
		add_action('wp_ajax_em_cart_page_contents','EM_Multiple_Bookings::cart_page_contents_ajax');
		add_action('wp_ajax_nopriv_em_cart_page_contents','EM_Multiple_Bookings::cart_page_contents_ajax');
		add_action('wp_ajax_em_checkout_page_contents','EM_Multiple_Bookings::checkout_page_contents_ajax');
		add_action('wp_ajax_nopriv_em_checkout_page_contents','EM_Multiple_Bookings::checkout_page_contents_ajax');
		add_action('wp_ajax_em_cart_contents','EM_Multiple_Bookings::cart_contents_ajax');
		add_action('wp_ajax_nopriv_em_cart_contents','EM_Multiple_Bookings::cart_contents_ajax');
		//cart content widget and shortcode
		add_action('wp_ajax_em_cart_widget_contents','EM_Multiple_Bookings::cart_widget_contents_ajax');
		add_action('wp_ajax_nopriv_em_cart_widget_contents','EM_Multiple_Bookings::cart_widget_contents_ajax');
		add_shortcode('em_cart_contents', 'EM_Multiple_Bookings::cart_contents');
		add_action('em_booking_js_footer', 'EM_Multiple_Bookings::em_booking_js_footer');
		//booking admin pages
		add_action('em_bookings_admin_page', 'EM_Multiple_Bookings::bookings_admin_notices'); //add MB warnings if booking is part of a bigger booking
		add_action('em_bookings_multiple_booking', 'EM_Multiple_Bookings::booking_admin',1,1); //handle page for showing a single multiple booking
		//booking table and csv filters
		add_filter('em_bookings_table_rows_col', array('EM_Multiple_Bookings','em_bookings_table_rows_col'),10,5);
		add_filter('em_bookings_table_cols_template', array('EM_Multiple_Bookings','em_bookings_table_cols_template'),10,2);
    }
    
    public static function em_get_booking($EM_Booking){
        if( !empty($EM_Booking->booking_id) && $EM_Booking->event_id == 0 ){
            return new EM_Multiple_Booking($EM_Booking);
        }
        return $EM_Booking;
    }
    
    public static function em_wp_localize_script( $vars ){
        if( get_option('dbem_multiple_bookings_redirect') ){
		    $vars['mb_redirect'] = get_post_permalink(get_option('dbem_multiple_bookings_checkout_page'));
        } 
	    $vars['mb_empty_cart'] = get_option('dbem_multiple_bookings_feedback_empty_cart');
	    return $vars;
    }
    
    /**
     * Starts a session, and returns whether session successfully started or not.
     * We can start a session after the headers are sent in this case, it's ok if a session was started earlier, since we're only grabbing server-side data
     */
    public static function session_start(){
        global $EM_Notices;
        if( !self::$session_started ){
            self::$session_started = @session_start();
        }
        return self::$session_started;
    }
    
    /**
     * Grabs multiple booking from session, or creates a new multiple booking object
     * @return EM_Multiple_Booking
     */
    public static function get_multiple_booking(){
        if( empty(self::$booking_data) ){
	        self::session_start();
	        if( !empty($_SESSION['em_multiple_bookings']) && get_class($_SESSION['em_multiple_bookings']) == 'EM_Multiple_Booking' ){
	            self::$booking_data = $_SESSION['em_multiple_bookings'];
	        }else{
	            self::$booking_data = $_SESSION['em_multiple_bookings'] = new EM_Multiple_Booking();
	        }
        }
        return self::$booking_data; 
    }
    
    public static function save_multiple_booking(){
        //probably won't be used due to object referencing in PHP5
        $_SESSION['em_multiple_bookings'] = self::get_multiple_booking();
    }
    
    public static function prevent_user_fields(){
		add_filter('emp_form_show_reg_fields', create_function('','return false;'));
    }
    
    public static function prevent_user_validation($result){
        self::prevent_user_fields();
        return $result;        
    }
    
    /**
     * Hooks into em_booking_add ajax action early and prevents booking from being saved to the database, instead it adds the booking to the bookings cart.
     * If this is not an AJAX request (due to JS issues) then a redirect is made after processing the booking.
     * @param EM_Event $EM_Event
     * @param EM_Booking $EM_Booking
     * @param boolean $post_validation
     */
    public static function em_booking_add( $EM_Event, $EM_Booking, $post_validation ){
        global $EM_Notices;
        $feedback = '';
        $result = false;
        if( self::session_start() ){
	        if ( $post_validation ) {
	            //booking can be added to cart
	            if( self::get_multiple_booking()->add_booking($EM_Booking) ){
	                $result = true;
		            $feedback = get_option('dbem_multiple_bookings_feedback_added');
		            $EM_Notices->add_confirm( $feedback, !defined('DOING_AJAX') ); //if not ajax, make this notice static for redirect
	            }else{
	                $result = false;
	                $feedback = '';
	                $EM_Notices->add_error( $EM_Booking->get_errors(), !defined('DOING_AJAX') ); //if not ajax, make this notice static for redirect
	            }
	        }else{
				$result = false;
				$EM_Notices->add_error( $EM_Booking->get_errors() );
			}
        }else{
			$EM_Notices->add_error(__('Sorry for the inconvenience, but we are having technical issues adding your bookings, please contact an administrator about this issue.','em-pro'), !defined('DOING_AJAX'));
        }
		ob_clean(); //em_booking_add uses ob_start(), so flush it here
		if( defined('DOING_AJAX') ){
			$return = array('result'=>$result, 'message'=>$feedback, 'errors'=> $EM_Notices->get_errors());
            echo EM_Object::json_encode(apply_filters('em_action_'.$_REQUEST['action'], $return, $EM_Booking));
		}else{
			wp_redirect(wp_get_referer());
		}
	    die();
    }
    
    /**
     * @param boolean $result
     * @param EM_Booking $EM_Booking
     */
    public static function em_booking_save($result, $EM_Booking){
        //only do this to a previously saved EM_Booking object, not newly added
        if( $result && get_class($EM_Booking) == 'EM_Booking' && $EM_Booking->previous_status !== false ){
            $EM_Multiple_Booking = self::get_main_booking( $EM_Booking );
            //if part of multiple booking, recalculate and save mb object too
            if( $EM_Multiple_Booking !== false ){
                $EM_Multiple_Booking->calculate_price();
                $EM_Multiple_Booking->save(false);
            }
        }
        return $result;
    }
    
    public static function remove_booking(){
        $EM_Multiple_Booking = self::get_multiple_booking();
		if( !empty($_REQUEST['event_id']) && !empty($EM_Multiple_Booking->bookings[$_REQUEST['event_id']]) ){
		    unset($EM_Multiple_Booking->bookings[$_REQUEST['event_id']]);
		    $EM_Multiple_Booking->calculate_price();
		    if( count($EM_Multiple_Booking->bookings) == 0 ) self::empty_cart();
		    $feedback = '';
		    $result = true;
		}else{
		    $feedback = __('Could not remove booking due to an unexpected error.', 'em-pro');
		    $result = false;
		}
        if( defined('DOING_AJAX') ){
        	$return = array('result'=>$result, 'message'=>$feedback);
        	echo EM_Object::json_encode(apply_filters('em_action_'.$_REQUEST['action'], $return, $EM_Multiple_Booking));
        }else{
        	wp_redirect(wp_get_referer());
        }
        die();
    }
    
    public static function empty_cart(){
	    self::session_start();
        unset($_SESSION['em_multiple_bookings']);
    }
    
    public static function empty_cart_ajax(){
	    self::empty_cart();
	    echo EM_Object::json_encode(array('success'=>true));
	    die();
    }
    
    public static function checkout(){
        global $EM_Notices, $EM_Booking;
		check_ajax_referer('emp_checkout');
		$EM_Booking = $EM_Multiple_Booking = self::get_multiple_booking();
        //remove filters so that our master booking validates user fields
		remove_action('em_booking_form_custom','EM_Multiple_Bookings::prevent_user_fields', 1); //prevent user fields from showing
		remove_filter('em_booking_validate', 'EM_Multiple_Bookings::prevent_user_validation', 1); //prevent user fields validation
		//now validate the master booking
        $EM_Multiple_Booking->get_post();
        $post_validation = $EM_Multiple_Booking->validate();
		//re-add filters to prevent individual booking problems
		add_action('em_booking_form_custom','EM_Multiple_Bookings::prevent_user_fields', 1); //prevent user fields from showing
		add_filter('em_booking_validate', 'EM_Multiple_Bookings::prevent_user_validation', 1); //prevent user fields validation
		$bookings_validation = $EM_Multiple_Booking->validate_bookings();
		//fire the equivalent of the em_booking_add action, but multiple variation 
		do_action('em_multiple_booking_add', $EM_Multiple_Booking->get_event(), $EM_Multiple_Booking, $post_validation && $bookings_validation); //get_event returns blank, just for backwards-compatabaility
		//proceed with saving bookings if all is well
		$result = false; $feedback = '';
        if( $bookings_validation && $post_validation ){
			//save user registration
       	    $registration = em_booking_add_registration($EM_Multiple_Booking);

        	//save master booking, which in turn saves the other bookings too
        	if( $registration && $EM_Multiple_Booking->save_bookings() ){
        	    $result = true;
        		$EM_Notices->add_confirm( $EM_Multiple_Booking->feedback_message );
        		$feedback = $EM_Multiple_Booking->feedback_message;
        		unset($_SESSION['em_multiple_bookings']); //we're done with this checkout!
        	}else{
        		$EM_Notices->add_error( $EM_Multiple_Booking->get_errors() );
        		$feedback = $EM_Multiple_Booking->feedback_message;
        	}
        	global $em_temp_user_data; $em_temp_user_data = false; //delete registered user temp info (if exists)
        }else{
            $EM_Notices->add_error( $EM_Multiple_Booking->get_errors() );
        }
		if( defined('DOING_AJAX') ){
		    if( $result ){
				$return = array('result'=>true, 'message'=>$feedback, 'checkout'=>true);
				echo EM_Object::json_encode(apply_filters('em_action_'.$_REQUEST['action'], $return, $EM_Multiple_Booking));
			}elseif( !$result ){
				$return = array('result'=>false, 'message'=>$feedback, 'errors'=>$EM_Notices->get_errors(), 'checkout'=>true);
				echo EM_Object::json_encode(apply_filters('em_action_'.$_REQUEST['action'], $return, $EM_Multiple_Booking));
			}
			die();
		}
    }
    
    /**
     * Hooks into the_content and checks if this is a checkout or cart page, and if so overwrites the page content with the relevant content. Uses same concept as em_content.
     * @param string $page_content
     * @return string
     */
    public static function pages($page_content) {
    	global $post, $wpdb, $wp_query, $EM_Event, $EM_Location, $EM_Category;
    	if( empty($post) ) return $page_content; //fix for any other plugins calling the_content outside the loop
    	$cart_page_id = get_option ( 'dbem_multiple_bookings_cart_page' );
    	$checkout_page_id = get_option( 'dbem_multiple_bookings_checkout_page' );
    	if( in_array($post->ID, array($cart_page_id, $checkout_page_id)) ){
    		ob_start();
    		if( $post->ID == $cart_page_id && $cart_page_id != 0 ){
    			self::cart_page();
    		}elseif( $post->ID == $checkout_page_id && $checkout_page_id != 0 ){
    			self::checkout_page();
    		}
    		$content = ob_get_clean();
    		//Now, we either replace CONTENTS or just replace the whole page
    		if( preg_match('/CONTENTS/', $page_content) ){
    			$content = str_replace('CONTENTS',$content,$page_content);
    		}
    		return $content;
    	}
    	return $page_content;
    }
    
    public static function cart_contents_ajax(){
    	emp_locate_template('multiple-bookings/cart-table.php', true);
    	die();
    }
    
    /* Checkout Page Code */
    
    public static function em_booking_js_footer(){
        if( !defined('EM_CART_JS_LOADED') ){
	        include('multiple-bookings.js');
			do_action('em_cart_js_footer');
			define('EM_CART_JS_LOADED',true);
        }
    }
	
	public static function checkout_page_contents_ajax(){
		emp_locate_template('multiple-bookings/page-checkout.php',true);
		die();
	}

	public static function checkout_page(){
	    if( !EM_Multiple_Bookings::get_multiple_booking()->validate_bookings_spaces() ){
	        global $EM_Notices;
	        $EM_Notices->add_error(EM_Multiple_Bookings::get_multiple_booking()->get_errors());
	    }
		//load contents if not using caching, do not alter this conditional structure as it allows the cart to work with caching plugins
		echo '<div class="em-checkout-page-contents" style="position:relative;">';
		if( !defined('WP_CACHE') || !WP_CACHE ){
			emp_locate_template('multiple-bookings/page-checkout.php',true);
		}else{
			echo '<p>'.get_option('dbem_multiple_bookings_feedback_loading_cart').'</p>';
		}
		echo '</div>';
		EM_Bookings::enqueue_js();
    }
    
    /* Shopping Cart Page */
	
	public static function cart_page_contents_ajax(){
		emp_locate_template('multiple-bookings/page-cart.php',true);
		die();
	}
        
    public static function cart_page(){
		if( !EM_Multiple_Bookings::get_multiple_booking()->validate_bookings_spaces() ){
			global $EM_Notices;
			$EM_Notices->add_error(EM_Multiple_Bookings::get_multiple_booking()->get_errors());
		}
		//load contents if not using caching, do not alter this conditional structure as it allows the cart to work with caching plugins
		echo '<div class="em-cart-page-contents" style="position:relative;">';
		if( !defined('WP_CACHE') || !WP_CACHE ){
			emp_locate_template('multiple-bookings/page-cart.php',true);
		}else{
			echo '<p>'.get_option('dbem_multiple_bookings_feedback_loading_cart').'</p>';
		}
		echo '</div>';
		if( !defined('EM_CART_JS_LOADED') ){
			//load 
			function em_cart_js_footer(){
				?>
				<script type="text/javascript">
					<?php include('multiple-bookings.js'); ?>
					<?php do_action('em_cart_js_footer'); ?>
				</script>
				<?php
			}
			add_action('wp_footer','em_cart_js_footer', 100);
			add_action('admin_footer','em_cart_js_footer');
			define('EM_CART_JS_LOADED',true);
		}
	}
    
    /* Shopping Cart Widget */
    
    public static function cart_widget_contents_ajax(){
        emp_locate_template('multiple-bookings/widget.php', true, array('instance'=>$_REQUEST));
        die();
    }
    
    public static function cart_contents( $instance ){
		$defaults = array(
				'title' => __('Event Bookings Cart','em-pro'),
				'format' => '#_EVENTLINK - #_EVENTDATES<ul><li>#_BOOKINGSPACES Spaces - #_BOOKINGPRICE</li></ul>',
				'loading_text' =>  __('Loading...','em-pro'),
				'checkout_text' => __('Checkout','em-pro'),
				'cart_text' => __('View Cart','em-pro'),
				'no_bookings_text' => __('No events booked yet','em-pro')
		);
		$instance = array_merge($defaults, (array) $instance);
		ob_start();
		?>
		<div class="em-cart-widget">
			<form>
				<input type="hidden" name="action" value="em_cart_widget_contents" />
				<input type="hidden" name="format" value="<?php echo $instance['format'] ?>" />
				<input type="hidden" name="cart_text" value="<?php echo $instance['cart_text'] ?>" />
				<input type="hidden" name="checkout_text" value="<?php echo $instance['checkout_text'] ?>" />
				<input type="hidden" name="no_bookings_text" value="<?php echo $instance['no_bookings_text'] ?>" />
				<input type="hidden" name="loading_text" value="<?php echo $instance['loading_text'] ?>" />
			</form>
			<div class="em-cart-widget-contents">
				<?php if( !defined('WP_CACHE') || !WP_CACHE ) emp_locate_template('multiple-bookings/widget.php', true, array('instance'=>$instance)); ?>
			</div>
		</div>
		<?php		
		if( !defined('EM_CART_WIDGET_JS_LOADED') ){ //load cart widget JS once per page
			function em_cart_widget_js_footer(){
				?>
				<script type="text/javascript">
					<?php include('cart-widget.js'); ?>
				</script>
				<?php
			}
			add_action('wp_footer','em_cart_widget_js_footer', 1000);
			define('EM_CART_WIDGET_JS_LOADED',true);
		}
		return ob_get_clean();
	}

    /*
     * ----------------------------------------------------------
    * Booking Table and CSV Export
    * ----------------------------------------------------------
    */
    
    function em_bookings_table_rows_col($value, $col, $EM_Booking, $EM_Bookings_Table, $csv){
        if( preg_match('/^mb_/', $col) ){
            $col = preg_replace('/^mb_/', '', $col);
	    	if( !empty($EM_Booking) && get_class($EM_Booking) != 'EM_Multiple_Booking' ){
				//is this part of a multiple booking?
				$EM_Multiple_Booking = self::get_main_booking( $EM_Booking );
				if( $EM_Multiple_Booking !== false ){
                	$EM_Form = EM_Booking_Form::get_form(false, get_option('dbem_multiple_bookings_form'));
                	if( array_key_exists($col, $EM_Form->form_fields) ){
                		$field = $EM_Form->form_fields[$col];
                		if( isset($EM_Multiple_Booking->booking_meta['booking'][$col]) ){
                			$value = $EM_Form->get_formatted_value($field, $EM_Multiple_Booking->booking_meta['booking'][$col]);
                		}
                	}
                }
            }
        }
    	return $value;
    }
    
    function em_bookings_table_cols_template($template, $EM_Bookings_Table){
    	$EM_Form = EM_Booking_Form::get_form(false, get_option('dbem_multiple_bookings_form'));
    	foreach($EM_Form->form_fields as $field_id => $field ){
            if( $EM_Form->is_normal_field($field) ){ //user fields already handled, htmls shouldn't show
                //prefix MB fields with mb_ to avoid clashes with normal booking forms
        		$template['mb_'.$field_id] = $field['label'];
        	}
    	}
    	return $template;
    }

    /*
     * ----------------------------------------------------------
    * Admin Stuff
    * ----------------------------------------------------------
    */
    public static function bookings_admin_notices(){
		global $EM_Booking, $EM_Notices;
		if( current_user_can('manage_others_bookings') ){
	    	if( !empty($EM_Booking) && get_class($EM_Booking) != 'EM_Multiple_Booking' ){
				//is this part of a multiple booking?
				$EM_Multiple_Booking = self::get_main_booking( $EM_Booking );
				if( $EM_Multiple_Booking !== false ){
					$EM_Notices->add_info(sprintf(__('This single booking is part of a larger booking made by this person at once. <a href="%s">View Main Booking</a>.','em-pro'), $EM_Multiple_Booking->get_admin_url()));
					echo $EM_Notices;
				}
			}elseif( !empty($EM_Booking) && get_class($EM_Booking) == 'EM_Multiple_Booking' ){
				$EM_Notices->add_info(__('This booking contains a set of bookings made by this person. To edit particular bookings click on the relevant links below.','em-pro'));
				echo $EM_Notices;
			}
		}
    }
    
    public static function get_main_booking( $EM_Booking ){
		global $wpdb;
		$main_booking_id = $wpdb->get_var($wpdb->prepare('SELECT booking_main_id FROM '.EM_BOOKINGS_RELATIONSHIPS_TABLE.' WHERE booking_id=%d', $EM_Booking->booking_id));
		if( !empty($main_booking_id) ){
			return new EM_Multiple_Booking($main_booking_id);
		}
		return false;
	}
    
    public static function booking_admin(){
		emp_locate_template('multiple-bookings/admin.php',true);
		if( !defined('EM_CART_JS_LOADED') ){
			//load 
			function em_cart_js_footer(){
				?>
				<script type="text/javascript">
					<?php include('multiple-bookings.js'); ?>
				</script>
				<?php
			}
			add_action('wp_footer','em_cart_js_footer');
			add_action('admin_footer','em_cart_js_footer');
			define('EM_CART_JS_LOADED',true);
		}
	}
}
EM_Multiple_Bookings::init();