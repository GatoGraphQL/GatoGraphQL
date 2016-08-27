<?php
class EM_ML_Options {
	/**
	 * @var array Array of option keys in wp_options that can be translated.
	 */
	static public $translatable_options;
	
    public function __construct(){
	    //define the translatable options for the plugin
		self::$translatable_options = apply_filters('em_ml_translatable_options', array(
			//GENERAL TAB
				//event submission forms
				'dbem_events_anonymous_result_success',
				'dbem_events_form_result_success',
				'dbem_events_form_result_success_updated',
			//FORMATTING TAB
				//events
				'dbem_event_list_groupby_format',
				'dbem_event_list_item_format_header',
				'dbem_event_list_item_format',
				'dbem_event_list_item_format_footer',
				'dbem_no_events_message',
				'dbem_list_date_title',
				'dbem_single_event_format',
		        'dbem_event_excerpt_format',
		        'dbem_event_excerpt_alt_format',
				//Search Form
				'dbem_search_form_submit',
				'dbem_search_form_advanced_hide',
				'dbem_search_form_advanced_show',
				'dbem_search_form_text_label',
				'dbem_search_form_categories_label',
				'dbem_search_form_category_label',
				'dbem_search_form_countries_label',
				'dbem_search_form_country_label',
				'dbem_search_form_regions_label',
				'dbem_search_form_region_label',
				'dbem_search_form_states_label',
				'dbem_search_form_state_label',
				'dbem_search_form_towns_label',
				'dbem_search_form_town_label',
				'dbem_search_form_geo_label',
				'dbem_search_form_geo_units_label',
				'dbem_search_form_dates_label',
				'dbem_search_form_dates_separator',
				//Date/Time
				'dbem_date_format',
				'dbem_date_format_js',
				'dbem_dates_separator',
				'dbem_time_format',
				'dbem_times_separator',
				'dbem_event_all_day_message',
				//Calendar
				'dbem_small_calendar_month_format',
				'dbem_small_calendar_event_title_format',
				'dbem_small_calendar_event_title_separator',
				'dbem_full_calendar_month_format',
				'dbem_full_calendar_event_format',
				'dbem_display_calendar_events_limit_msg',
				//Ical
				'dbem_ical_description_format',
				'dbem_ical_real_description_format',
				'dbem_ical_location_format',				
				//Locations
				'dbem_location_list_item_format_header',
				'dbem_location_list_item_format',
				'dbem_location_list_item_format_footer',
				'dbem_no_locations_message',
				'dbem_location_page_title_format',
				'dbem_single_location_format',
				'dbem_location_event_list_item_header_format',
				'dbem_location_event_list_item_format',
				'dbem_location_event_list_item_footer_format',
				'dbem_location_no_events_message',
				'dbem_location_event_single_format',
				'dbem_location_no_event_message',
				//Categories
				'dbem_categories_list_item_format_header',
				'dbem_categories_list_item_format',
				'dbem_categories_list_item_format_footer',
				'dbem_no_categories_message',
				'dbem_category_page_title_format',
				'dbem_category_page_format',
				'dbem_category_event_list_item_header_format',
				'dbem_category_event_list_item_format',
				'dbem_category_event_list_item_footer_format',
				'dbem_category_no_events_message',
				'dbem_category_no_event_message',
				'dbem_category_event_single_format',
				//Tags
				'dbem_tags_list_item_format_header',
				'dbem_tags_list_item_format',
				'dbem_tags_list_item_format_footer',
				'dbem_no_tags_message',
				'dbem_tag_page_title_format',
				'dbem_tag_page_format',
				'dbem_tag_event_list_item_header_format',
				'dbem_tag_event_list_item_format',
				'dbem_tag_event_list_item_footer_format',
				'dbem_tag_no_events_message',
				'dbem_tag_event_single_format',
				'dbem_tag_no_event_message',
				//RSS
				'dbem_rss_main_description',
				'dbem_rss_main_title',
				'dbem_rss_title_format',
				'dbem_rss_description_format',
				//Maps
				'dbem_map_text_format',
				'dbem_location_baloon_format',
			//Bookings
				//Pricing Options
				'dbem_bookings_currency_thousands_sep',
				'dbem_bookings_currency_decimal_point',
				'dbem_bookings_currency_format',
				//booking feedback messages
				'dbem_booking_feedback_cancelled',
				'dbem_booking_warning_cancel',
				'dbem_bookings_form_msg_disabled',
				'dbem_bookings_form_msg_closed',
				'dbem_bookings_form_msg_full',
				'dbem_bookings_form_msg_attending',
				'dbem_bookings_form_msg_bookings_link',
				'dbem_booking_feedback',
				'dbem_booking_feedback_pending',
				'dbem_booking_feedback_full',
				'dbem_booking_feedback_error',
				'dbem_booking_feedback_email_exists',
				'dbem_booking_feedback_log_in',
				'dbem_booking_feedback_nomail',
				'dbem_booking_feedback_already_booked',
				'dbem_booking_feedback_min_space',
				'dbem_booking_feedback_spaces_limit',
				'dbem_booking_button_msg_book',
				'dbem_booking_button_msg_booking',
				'dbem_booking_button_msg_booked',
				'dbem_booking_button_msg_already_booked',
				'dbem_booking_button_msg_error',
				'dbem_booking_button_msg_full',
				'dbem_booking_button_msg_cancel',
				'dbem_booking_button_msg_canceling',
				'dbem_booking_button_msg_cancelled',
				'dbem_booking_button_msg_cancel_error',
				//booking form options
				'dbem_bookings_submit_button',
			//Emails
				//booking email templates
				'dbem_bookings_contact_email_pending_subject',
    			'dbem_bookings_contact_email_pending_body',
    			'dbem_bookings_contact_email_confirmed_subject',
    			'dbem_bookings_contact_email_confirmed_body',
    			'dbem_bookings_contact_email_rejected_subject',
    			'dbem_bookings_contact_email_rejected_body',
    			'dbem_bookings_contact_email_cancelled_subject',
    			'dbem_bookings_contact_email_cancelled_body',
				'dbem_bookings_email_confirmed_subject',
				'dbem_bookings_email_confirmed_body',
				'dbem_bookings_email_pending_subject',
				'dbem_bookings_email_pending_body',
				'dbem_bookings_email_rejected_subject',
				'dbem_bookings_email_rejected_body',
				'dbem_bookings_email_cancelled_subject',
				'dbem_bookings_email_cancelled_body',
				//event submission templates
				'dbem_event_submitted_email_subject',
				'dbem_event_submitted_email_body',
				'dbem_event_resubmitted_email_subject',
				'dbem_event_resubmitted_email_body',
				'dbem_event_published_email_subject',
				'dbem_event_published_email_body',
				'dbem_event_approved_email_subject',
				'dbem_event_approved_email_body',
				'dbem_event_reapproved_email_subject',
				'dbem_event_reapproved_email_body',
				//Registration Emails
				'dbem_bookings_email_registration_subject',
				'dbem_bookings_email_registration_body'
		));
		//When in the EM settings page translatable values should be shown in the currently active language
		if( is_admin() && !empty($_REQUEST['page']) && $_REQUEST['page'] == 'events-manager-options' ) return;
		//add a hook for all trnalsateable values
		if( EM_ML::$current_language != EM_ML::$wplang ){
		 	foreach( self::$translatable_options as $option ){
		 	    add_filter('pre_option_'.$option, array(&$this, 'pre_option_'.$option), 1,1);
	 		}
		}
		//Switch EM page IDs to translated versions if they exist, so e.g. the events page in another language grabs the right translated page format if available
        add_filter('option_dbem_events_page','EM_ML_Options::get_translated_page');
        add_filter('option_dbem_locations_page','EM_ML_Options::get_translated_page');
        add_filter('option_dbem_categories_page','EM_ML_Options::get_translated_page');
        add_filter('option_dbem_tags_page','EM_ML_Options::get_translated_page');
        add_filter('option_dbem_edit_events_page','EM_ML_Options::get_translated_page');
        add_filter('option_dbem_edit_locations_page','EM_ML_Options::get_translated_page');
        add_filter('option_dbem_edit_bookings_page','EM_ML_Options::get_translated_page');
        add_filter('option_dbem_my_bookings_page','EM_ML_Options::get_translated_page');
    }
	
	/**
	 * Assumes calls are from the pre_option_ filter which were registered during the init() function. 
	 * This takes the filter name and searches for an equivalent translated option if it exists.
	 * 
	 * @param string $filter_name The name of the filter being applied.
	 * @param mixed $value Supplied filter value.
	 * @return mixed Returns either translated data or the supplied value.
	 */
	public function __call($filter_name, $value){
		if( strstr($filter_name, 'pre_option_') !== false ){
		    //we're calling an option to be overridden by the default language
		    $option_name = str_replace('pre_option_','',$filter_name);
		    //don't use EM_ML::get_option as it creates an endless loop for options without a translation
			$option_langs = get_option($option_name.'_ml', array());
			if( !empty($option_langs[EM_ML::$current_language]) ){
				return $option_langs[EM_ML::$current_language];
			}
		}
		return $value[0];
	}
    
	/* START wp_options hooks */
	/**
	 * Gets an option in a specific language. Similar to get_option but will return either the translated option if it exists
	 * @param string $option
	 * @param string $lang
	 * @param boolean $return_original
	 * @return mixed
	 */
	public static function get_option($option, $lang = false, $return_original = true){
		if( self::is_option_translatable($option) ){
			$option_langs = get_option($option.'_ml', array());
			if( empty($lang) ) $lang = self::$current_language;
			if( !empty($option_langs[$lang]) ){
				return $option_langs[$lang];
			}
		}
		return $return_original ? get_option($option):'';
	}

	/**
	 * Returns whether or not this option name is translatable.
	 * @param string $option Option Name
	 * @return boolean
	 */
	public static function is_option_translatable($option){
		return count(EM_ML::$langs) > 0 && in_array($option, self::$translatable_options);
	}
	
	/* END wp_options functions */

    /**
     * Takes a page post_id and returns the translated version post_id of the language currently being viewed by the user. 
     * Used to detect whether we're on an events listing page for example, where we would normally override the content with our formats.
     * @param int $post_id
     * @return int
     * @uses EM_ML::get_translated_post_id()
     */
    public static function get_translated_page($post_id){
    	return EM_ML::get_translated_post_id($post_id, 'page');
    }
}
$EM_ML_Options = new EM_ML_Options();