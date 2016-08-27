<?php
/*
 * Since translations of the original event post share certain information such as times, bookings and location, certain placeholders should be manipulated
 * when showing a translation of the event.
 * 
 * At this time, given that times etc. are copied to the translation we don't need to override anything, but you can add your own placeholders here via the 
 * em_ml_placeholders_event_placeholders filter and those placeholders will refer to the original event for data. 
 */
class EM_ML_Placeholders {

	public static $event_placeholders = array();
	
	public static function init(){
	    self::$event_placeholders = apply_filters('em_ml_placeholders_event_placeholders',self::$event_placeholders);
	    if( !empty(self::$event_placeholders) ){
	        add_filter('em_event_output_placeholder','EM_ML_Placeholders::override_placeholders',100,3); //override bookign form
	    }
	}
	
	/**
	 * Certain placeholders, specifically booking placeholders, will take information from the original event, so we generate the 
	 * @param string $replace
	 * @param EM_Event $EM_Event
	 * @param string $full_result
	 * @return string
	 */
	public static function override_placeholders($replace, $EM_Event, $full_result){
		global $em_wpml_original_events_cache;
		if( in_array($full_result, EM_ML_Placeholders::$event_placeholders) ){
			$event = EM_ML::get_original_event($EM_Event); //get the master event info, for later use
			if( $event->event_id != $EM_Event->event_id ){
			    remove_filter('em_event_output_placeholder','EM_ML_Placeholders::override_placeholders',100,3); //override bookign form
				$replace = $event->output($full_result);
			    add_filter('em_event_output_placeholder','EM_ML_Placeholders::override_placeholders',100,3); //override bookign form
			}
		}
		return $replace;
	}
}
EM_ML_Placeholders::init();