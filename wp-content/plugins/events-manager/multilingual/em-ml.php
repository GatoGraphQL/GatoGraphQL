<?php
/**
 * Abstract layer allowing for certain aspects of Events Manager to be translateable. Useful for translation plugins to hook into.
 */
class EM_ML{
    /**
     * @var boolean Flag confirming whether this class has been initialized yet.
     */
    static public $init;
	/**
	 * @var array Array of available languages, where keys are the locales and the values are the displayable names of the language e.g. array('fr_FR' => 'French');
	 */
	static public $langs = array();
	/**
	 * @var string The main language of this blog, meaning the language used should no multilingual plugin be installed. Example: 'en_US' for American English.
	 */
	static public $wplang;
	/**
	 * @var string The currently active language of this site, meaning the language being displayed to the user. Example: 'en_US' for American English.
	 */
	static public $current_language;
	/**
	 * @var boolean Flag for whether EM is multilingual ready, false by default, set after init() has been executed first time.
	 */
	static public $is_ml = false;

	public static function init(){
	    if( !empty(self::$init) ) return;
		
		//Determine the available languages and the currently displayed locale for this site.
		self::$langs = apply_filters('em_ml_langs', array());
		self::$wplang = apply_filters('em_ml_wplang',get_locale());
		self::$current_language = !empty($_REQUEST['em_lang']) && array_key_exists($_REQUEST['em_lang'], self::$langs) ? $_REQUEST['em_lang']:get_locale();
		self::$current_language = apply_filters('em_ml_current_language',self::$current_language);
		
		//proceed with loading the plugin, we don't need to deal with the rest of this if no languages were defined by an extending class
		if( count(self::$langs) > 0 ) {
		    //set flag to prevent unecessary counts
		    self::$is_ml = true;
		    do_action('em_ml_pre_init'); //only initialize when this is a MultiLingual instance 
    		//make sure options are being translated immediately if needed
    		include(EM_DIR.'/multilingual/em-ml-options.php');
    		//load all the extra ML helper classes
    	    if( is_admin() ){
    	        include(EM_DIR.'/multilingual/em-ml-admin.php');
    	    }
    	    include(EM_DIR.'/multilingual/em-ml-bookings.php');
    	    include(EM_DIR.'/multilingual/em-ml-io.php');
    	    include(EM_DIR.'/multilingual/em-ml-placeholders.php');
    	    include(EM_DIR.'/multilingual/em-ml-search.php');
    	    
    		//change some localized script vars
    		add_filter('em_wp_localize_script', 'EM_ML::em_wp_localize_script');
		}
		self::$init = true;
		if( self::$is_ml ) do_action('em_ml_init'); //only initialize when this is a MultiLingual instance 
	}
    
    /**
     * Localizes the script variables
     * @param array $em_localized_js
     * @return array
     */
    public static function em_wp_localize_script($em_localized_js){
        $em_localized_js['ajaxurl'] = admin_url('admin-ajax.php?em_lang='.self::$current_language);
        $em_localized_js['locationajaxurl'] = admin_url('admin-ajax.php?action=locations_search&em_lang='.self::$current_language);
		if( get_option('dbem_rsvp_enabled') ){
		    $em_localized_js['bookingajaxurl'] = admin_url('admin-ajax.php?em_lang='.self::$current_language);
		}
        return $em_localized_js;
    }
	
	/**
	 * Gets the available languages this site can display.
	 * @return array EM_ML::$langs;
	 */
	public static function get_langs(){
		return self::$langs;
	}

    /**
	 * Shortcut for EM_ML_Options::get_option. Gets translated option.
	 * @uses EM_ML_Options::get_option()
	 * @param string $option
	 * @param string $lang
	 * @param boolean $return_original
	 * @return mixed
	 */
	public static function get_option($option, $lang = false, $return_original = true){
	    if( !self::$is_ml ) return get_option($option);
		return EM_ML_Options::get_option($option, $lang, $return_original);
	}

	/**
	 * Returns whether or not this option name is translatable.
	 * @uses EM_ML_Options::is_option_translatable()
	 * @param string $option Option Name
	 * @return boolean
	 */
	public static function is_option_translatable($option){
	    if( !self::$is_ml ) return false;
		return EM_ML_Options::is_option_translatable($option);
	}
	
	/* From here on you see options that can/should be filtered to provide the relevant translation conversions. The above functions handle the rest... */
    
    /**
     * Takes a post ID for a CPT of $post_type, returns the post ID of a translation of the language currently being viewed.
     * If post ID is already in the same language being viewed, it is returned. If no translation is availalbe, returns false.
     * @param int $post_id          The post ID you want to find the translation for currently viewed language.
     * @param string $post_type     Optional. The post type of the post ID supplied. Avoids extra DB calls if provided.
     * @return int|bool
     */
    public static function get_translated_post_id($post_id, $post_type = ''){
        if( empty($post_type) ) $post_type = get_post_type($post_id);
        return apply_filters('em_ml_get_translated_post_id',$post_id, $post_type);
    }
	
	/**
	 * Returns the language of the object, in a WPLANG compatible format
	 * @param EM_Event|EM_Location $object
	 * @return mixed
	 */
	public static function get_the_language($object){
	    if( empty($type) ) $type = EM_POST_TYPE_EVENT;
	    return apply_filters('em_ml_get_the_language',self::$wplang, $object);
	}
	
	
	/**
	 * Gets the post ID of an EM_Event or EM_Location $object of the desired language $lang, returns same object if already in requested language.
	 * If $language is false or not provided, another translation of $object should be returned with precedence for the main language of this blog if $object isn't in that language.  
	 * @param EM_Event|EM_Location $object
	 * @param string $language Optional. WPLANG accepted value or false
	 * @return EM_Event|EM_Location
	 */
	public static function get_translation_id($object, $language = false){
	    return apply_filters('em_ml_get_translation_id', $object, $language);
	}
	
	/**
	 * Gets and EM_Event or EM_Location object of the desired language $lang for the passed EM_Event or EM_Location $object, returns same object if already in requested language.
	 * If $language is false or not provided, another translation of $object should be returned with precedence for the main language of this blog if $object isn't in that language.
	 * This function ouptut does not need to be filtered by a ML plugin if em_ml_get_tralsation_id is already filtered and efficiently provides an ID without loading the object too (otherwise object may be loaded twice unecessarily).
	 * @uses EM_ML::get_translation_id()  
	 * @param EM_Event|EM_Location $object
	 * @param string $language Optional. WPLANG accepted value or false
	 * @return EM_Event|EM_Location
	 */
	public static function get_translation($object, $language = false){
	    $translated_id = self::get_translation_id($object, $language);
	    $translated_object = $object; //return $object if the condition below isn't met
	    if( $object->post_id != $translated_id ){ 
    	    if( $object->post_type == EM_POST_TYPE_EVENT ) $translated_object = em_get_event($translated_id,'post_id');
    	    if( $object->post_type == EM_POST_TYPE_LOCATION ) $translated_object = em_get_location($translated_id,'post_id');
	    }
	    return apply_filters('em_ml_get_translation', $translated_object, $object, $language);
	}

	
	/* START original object determining functions - These first two must be overridden via filters for any functionality to truly work. */
	public static function get_original( $object ){
		return apply_filters('em_ml_get_original', $object );
	}

	public static function is_original( $object ){
		return apply_filters('em_ml_is_original',true, $object);
	}
	/**
	 * Shortcut for EM_ML::get_original() to aid IDE semantics.
	 * @see EM_ML::get_original()
	 * @param EM_Event $EM_Event
	 * @return EM_Event
	 */
	public static function get_original_event( $EM_Event ){
	    return self::get_original( $EM_Event );
	}
	/**
	 * Shortcut for EM_ML::get_original() to aid IDE semantics.
	 * @see EM_ML::get_original()
	 * @param EM_Location $EM_Location
	 * @return EM_Location
	 */
	public static function get_original_location( $EM_Location ){
	    return self::get_original( $EM_Location );
	}
	/* END original object determining functions */
}
add_action('init','EM_ML::init'); //other plugins may want to do this before we do, that's ok!