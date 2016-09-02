<?php
/*
Plugin Name: Events Manager Pro
Plugin URI: http://wp-events-plugin.com
Description: Supercharge the Events Manager free plugin with extra feature to make your events even more successful!
Author: NetWebLogic
Author URI: http://wp-events-plugin.com/
Version: 2.3.7.1

Copyright (C) 2011 NetWebLogic LLC
*/
define('EMP_VERSION', 2.36);
define('EM_MIN_VERSION', 5.44);
define('EMP_SLUG', plugin_basename( __FILE__ ));
class EM_Pro {

	/**
	 * em_pro_data option
	 * @var array
	 */
	var $data;

	/**
	 * Class initialization
	 */
	function EM_Pro() {
		global $wpdb;
		//Set when to run the plugin : after EM is loaded.
		add_action( 'plugins_loaded', array(&$this,'init') );
	}

	/**
	 * Actions to take upon initial action hook
	 */
	function init(){
		global $wpdb;
		//Define some tables
		if( EM_MS_GLOBAL ){
			$prefix = $wpdb->base_prefix;
		}else{
			$prefix = $wpdb->prefix;
		}
		define('EM_TRANSACTIONS_TABLE', $prefix.'em_transactions'); //TABLE NAME
		define('EM_EMAIL_QUEUE_TABLE', $prefix.'em_email_queue'); //TABLE NAME
		define('EM_COUPONS_TABLE', $prefix.'em_coupons'); //TABLE NAME
		define('EM_BOOKINGS_RELATIONSHIPS_TABLE', $prefix.'em_bookings_relationships'); //TABLE NAME
		//check that EM is installed
		if(!defined('EM_VERSION')){
			add_action('admin_notices',array(&$this,'em_install_warning'));
			add_action('network_admin_notices',array(&$this,'em_install_warning'));
			return false; //don't load EMP further
		}elseif( EM_MIN_VERSION > EM_VERSION ){
			//check that EM is up to date
			add_action('admin_notices',array(&$this,'em_version_warning'));
			add_action('network_admin_notices',array(&$this,'em_version_warning'));
		}
	    if( is_admin() ){ //although activate_plugins would be beter here, superusers don't visit every single site on MS
			add_action('init', array($this, 'install'),2);
	    }
		//Add extra Styling/JS
		// Hack PoP Plug-in: this must be a bug, changed dbem_disable_css to dbem_css_limit (as in events-manager.php)
		//if( !get_option('dbem_disable_css') ){
	    if( !get_option('dbem_css_limit') ){
			add_action('wp_head', array(&$this,'wp_head'));
			add_action('admin_head', array(&$this,'admin_head'));
		}
		add_action('em_public_script_deps', array(&$this,'enqueue_script_dependencies'));
		add_action('em_enqueue_scripts', array(&$this,'enqueue_script'), 1); //added only when EM adds its own scripts
		add_action('em_enqueue_admin_scripts', array(&$this,'enqueue_script'), 1); //added only when EM adds its own scripts
		add_action('admin_init', array(&$this,'enqueue_admin_script'), 1);		
	    add_filter('em_wp_localize_script', array(&$this,'em_wp_localize_script'),10,1);
		//includes
		include('emp-forms.php'); //form editor
		if( is_admin() ){
		    include('emp-admin.php');
		}
		//add-ons
		include('add-ons/gateways/gateways.php');
		include('add-ons/bookings-form/bookings-form.php');
		include('add-ons/coupons/coupons.php');
		include('add-ons/emails/emails.php');
		include('add-ons/user-fields.php');
        if( get_option('dbem_multiple_bookings') ){
		    include('add-ons/multiple-bookings/multiple-bookings.php');
        }
		//MS Specific stuff
		if( is_multisite() ){
			add_filter('em_ms_globals',array(&$this,'em_ms_globals'));
		}
	}
	
	function install(){
	    if( current_user_can('list_users') ){
		    //Upgrade/Install Routine
	    	$old_version = get_option('em_pro_version');
	    	if( EMP_VERSION > $old_version || $old_version == '' || (is_multisite() && !EM_MS_GLOBAL && get_option('emp_ms_global_install')) ){
	    		require_once('emp-install.php');
	    		emp_install();
	    	}
	    }
	}

	function em_ms_globals($globals){
		$globals[] = 'dbem_pro_api_key';
		return $globals;
	}
	
	function enqueue_script_dependencies( $scripts ){
	    global $wp_query;
	    if( ( !empty($wp_query->get_queried_object()->post_type) && $wp_query->get_queried_object()->post_type == EM_POST_TYPE_EVENT) || (!empty($_REQUEST['event_id']) && !empty($_REQUEST['action']) && $_REQUEST['action'] == 'manual_booking') ){
	        $scripts['jquery-ui-datepicker'] = 'jquery-ui-datepicker'; //for the booking form
	    }
	    return $scripts;
	}

	function enqueue_script(){
		wp_enqueue_script('events-manager-pro', plugins_url('includes/js/events-manager-pro.js',__FILE__), array('jquery')); //jQuery will load as dependency
	}

	function enqueue_admin_script(){
	    global $pagenow;
	    if( !empty($_REQUEST['page']) && ($_REQUEST['page'] == 'events-manager-forms-editor' || ($_REQUEST['page'] == 'events-manager-bookings' && !empty($_REQUEST['action']) && $_REQUEST['action'] == 'manual_booking')) ){
			wp_enqueue_script('events-manager-pro', plugins_url('includes/js/events-manager-pro.js',__FILE__), array('jquery', 'jquery-ui-core','jquery-ui-widget','jquery-ui-position')); //jQuery will load as dependency	        
	    }
	    if( $pagenow == 'user-edit.php' ){
	        //need to include the em script for dates
	        EM_Scripts_and_Styles::admin_enqueue();
	    }
	}
	
	function em_wp_localize_script( $vars ){
	    $vars['cache'] = defined('WP_CACHE') && WP_CACHE;
	    return $vars;
	}

	/**
	 * For now we'll just add style and js here, since it's so minimal
	 */
	function wp_head(){
		?>
		<style type="text/css">
		.em-booking-form span.form-tip { text-decoration:none; border-bottom:1px dotted #aaa; padding-bottom:2px; }
		.input-group .em-date-range input { width:100px; }
		.input-group .em-time-range input { width:80px; }
		 div.em-gateway-buttons { height:50px; width: 100%; }
		 div.em-gateway-buttons .first { padding-left:0px; margin-left:0px; border-left:none; }
		 div.em-gateway-button { float:left; padding-left:20px; margin-left:20px; border-left:1px solid #777; }
		/* Cart CSS */
		.em-cart-widget-content .em-cart-widget-total { border-top:1px solid #efefef; margin-top:5px; }
		.em-cart-actions { text-align:right; margin:10px 0px;}
		 /* Checkout Form CSS */
		.em-cart-table { border-collapse:collapse; border-spacing:0; width:100%; }		 
		.em-cart-table { border-collapse:0px; }
		.em-cart-table th, table.em-cart-table td { border:1px solid #efefef; padding:10px; }
		.em-cart-table th { background-color:#eee; text-align:center; }
		.em-cart-table td { background-color:#fff; }
		.em-cart-table th.em-cart-title-event { text-align:left; }
		.em-cart-table tbody.em-cart-totals th { text-align:right; padding-right:20px; }
		.em-cart-table .em-cart-table-details-hide { display:none; visibility:none; }
		.em-cart-table .em-cart-table-event-details { display:none; visibility:none; }
		.em-cart-table .em-cart-table-event-title { font-weight:bold; }
		.em-cart-table .em-cart-table-spaces { text-align:center; }
		.em-cart-table .em-cart-table-price, tbody.em-cart-totals td { text-align:right; }
		.em-cart-table-event-details .em-cart-table-ticket { font-weight:bold; }
		.em-cart-info .em-cart-info-title { text-decoration:underline; }
		.em-cart-info .em-cart-info-booking-title { font-weight:bold; }
		.em-cart-info .em-cart-info-values label { font-style:italic; display: inline-block; width:150px; }		 
		/*! qTip2 v2.0.0 | http://craigsworks.com/projects/qtip2/ | Licensed MIT, GPL */#qtip-rcontainer{position:absolute;left:-28000px;top:-28000px;display:block;visibility:hidden}#qtip-rcontainer .ui-tooltip{display:block!important;visibility:hidden!important;position:static!important;float:left!important}.ui-tooltip,.qtip{position:absolute;left:-28000px;top:-28000px;display:none;max-width:280px;min-width:50px;font-size:10.5px;line-height:12px}.ui-tooltip-content{position:relative;padding:5px 9px;overflow:hidden;text-align:left;word-wrap:break-word}.ui-tooltip-titlebar{position:relative;min-height:14px;padding:5px 35px 5px 10px;overflow:hidden;border-width:0 0 1px;font-weight:700}.ui-tooltip-titlebar+.ui-tooltip-content{border-top-width:0!important}.ui-tooltip-titlebar .ui-state-default{position:absolute;right:4px;top:50%;margin-top:-9px;cursor:pointer;outline:medium none;border-width:1px;border-style:solid}* html .ui-tooltip-titlebar .ui-state-default{top:16px}.ui-tooltip-titlebar .ui-icon,.ui-tooltip-icon .ui-icon{display:block;text-indent:-1000em;direction:ltr}.ui-tooltip-icon,.ui-tooltip-icon .ui-icon{-moz-border-radius:3px;-webkit-border-radius:3px;border-radius:3px;text-decoration:none}.ui-tooltip-icon .ui-icon{width:18px;height:14px;text-align:center;text-indent:0;font:normal bold 10px/13px Tahoma,sans-serif;color:inherit;background:transparent none no-repeat -100em -100em}.ui-tooltip-focus{}.ui-tooltip-hover{}.ui-tooltip-default{border-width:1px;border-style:solid;border-color:#F1D031;background-color:#FFFFA3;color:#555}.ui-tooltip-default .ui-tooltip-titlebar{background-color:#FFEF93}.ui-tooltip-default .ui-tooltip-icon{border-color:#CCC;background:#F1F1F1;color:#777}.ui-tooltip-default .ui-tooltip-titlebar .ui-state-hover{border-color:#AAA;color:#111}/*! Light tooltip style */.ui-tooltip-light{background-color:#fff;border-color:#E2E2E2;color:#454545}.ui-tooltip-light .ui-tooltip-titlebar{background-color:#f1f1f1}/*! Dark tooltip style */.ui-tooltip-dark{background-color:#505050;border-color:#303030;color:#f3f3f3}.ui-tooltip-dark .ui-tooltip-titlebar{background-color:#404040}.ui-tooltip-dark .ui-tooltip-icon{border-color:#444}.ui-tooltip-dark .ui-tooltip-titlebar .ui-state-hover{border-color:#303030}/*! Cream tooltip style */.ui-tooltip-cream{background-color:#FBF7AA;border-color:#F9E98E;color:#A27D35}.ui-tooltip-cream .ui-tooltip-titlebar{background-color:#F0DE7D}.ui-tooltip-cream .ui-state-default .ui-tooltip-icon{background-position:-82px 0}/*! Red tooltip style */.ui-tooltip-red{background-color:#F78B83;border-color:#D95252;color:#912323}.ui-tooltip-red .ui-tooltip-titlebar{background-color:#F06D65}.ui-tooltip-red .ui-state-default .ui-tooltip-icon{background-position:-102px 0}.ui-tooltip-red .ui-tooltip-icon{border-color:#D95252}.ui-tooltip-red .ui-tooltip-titlebar .ui-state-hover{border-color:#D95252}/*! Green tooltip style */.ui-tooltip-green{background-color:#CAED9E;border-color:#90D93F;color:#3F6219}.ui-tooltip-green .ui-tooltip-titlebar{background-color:#B0DE78}.ui-tooltip-green .ui-state-default .ui-tooltip-icon{background-position:-42px 0}/*! Blue tooltip style */.ui-tooltip-blue{background-color:#E5F6FE;border-color:#ADD9ED;color:#5E99BD}.ui-tooltip-blue .ui-tooltip-titlebar{background-color:#D0E9F5}.ui-tooltip-blue .ui-state-default .ui-tooltip-icon{background-position:-2px 0}.ui-tooltip-shadow{-webkit-box-shadow:1px 1px 3px 1px rgba(0,0,0,.15);-moz-box-shadow:1px 1px 3px 1px rgba(0,0,0,.15);box-shadow:1px 1px 3px 1px rgba(0,0,0,.15)}.ui-tooltip-rounded,.ui-tooltip-tipsy,.ui-tooltip-bootstrap{-moz-border-radius:5px;-webkit-border-radius:5px;border-radius:5px}.ui-tooltip-youtube{-moz-border-radius:2px;-webkit-border-radius:2px;border-radius:2px;-webkit-box-shadow:0 0 3px #333;-moz-box-shadow:0 0 3px #333;box-shadow:0 0 3px #333;color:#fff;border-width:0;background:#4A4A4A;background-image:-webkit-gradient(linear,left top,left bottom,color-stop(0, #4A4A4A),color-stop(100%,black));background-image:-webkit-linear-gradient(top, #4A4A4A 0,black 100%);background-image:-moz-linear-gradient(top, #4A4A4A 0,black 100%);background-image:-ms-linear-gradient(top, #4A4A4A 0,black 100%);background-image:-o-linear-gradient(top, #4A4A4A 0,black 100%)}.ui-tooltip-youtube .ui-tooltip-titlebar{background-color:#4A4A4A;background-color:rgba(0,0,0,0)}.ui-tooltip-youtube .ui-tooltip-content{padding:.75em;font:12px arial,sans-serif;filter:progid:DXImageTransform.Microsoft.Gradient(GradientType=0, StartColorStr=#4a4a4a, EndColorStr=#000000);-ms-filter:"progid:DXImageTransform.Microsoft.Gradient(GradientType=0, StartColorStr=#4a4a4a, EndColorStr=#000000);"}.ui-tooltip-youtube .ui-tooltip-icon{border-color:#222}.ui-tooltip-youtube .ui-tooltip-titlebar .ui-state-hover{border-color:#303030}.ui-tooltip-jtools{background:#232323;background:rgba(0,0,0,.7);background-image:-webkit-gradient(linear,left top,left bottom,from( #717171),to( #232323));background-image:-moz-linear-gradient(top, #717171, #232323);background-image:-webkit-linear-gradient(top, #717171, #232323);background-image:-ms-linear-gradient(top, #717171, #232323);background-image:-o-linear-gradient(top, #717171, #232323);border:2px solid #ddd;border:2px solid rgba(241,241,241,1);-moz-border-radius:2px;-webkit-border-radius:2px;border-radius:2px;-webkit-box-shadow:0 0 12px #333;-moz-box-shadow:0 0 12px #333;box-shadow:0 0 12px #333}.ui-tooltip-jtools .ui-tooltip-titlebar{background-color:transparent;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#717171, endColorstr=#4A4A4A);-ms-filter:"progid:DXImageTransform.Microsoft.gradient(startColorstr=#717171, endColorstr=#4A4A4A)"}.ui-tooltip-jtools .ui-tooltip-content{filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#4A4A4A, endColorstr=#232323);-ms-filter:"progid:DXImageTransform.Microsoft.gradient(startColorstr=#4A4A4A, endColorstr=#232323)"}.ui-tooltip-jtools .ui-tooltip-titlebar,.ui-tooltip-jtools .ui-tooltip-content{background:transparent;color:#fff;border:0 dashed transparent}.ui-tooltip-jtools .ui-tooltip-icon{border-color:#555}.ui-tooltip-jtools .ui-tooltip-titlebar .ui-state-hover{border-color:#333}.ui-tooltip-cluetip{-webkit-box-shadow:4px 4px 5px rgba(0,0,0,.4);-moz-box-shadow:4px 4px 5px rgba(0,0,0,.4);box-shadow:4px 4px 5px rgba(0,0,0,.4);background-color:#D9D9C2;color:#111;border:0 dashed transparent}.ui-tooltip-cluetip .ui-tooltip-titlebar{background-color:#87876A;color:#fff;border:0 dashed transparent}.ui-tooltip-cluetip .ui-tooltip-icon{border-color:#808064}.ui-tooltip-cluetip .ui-tooltip-titlebar .ui-state-hover{border-color:#696952;color:#696952}.ui-tooltip-tipsy{background:#000;background:rgba(0,0,0,.87);color:#fff;border:0 solid transparent;font-size:11px;font-family:'Lucida Grande',sans-serif;font-weight:700;line-height:16px;text-shadow:0 1px black}.ui-tooltip-tipsy .ui-tooltip-titlebar{padding:6px 35px 0 10;background-color:transparent}.ui-tooltip-tipsy .ui-tooltip-content{padding:6px 10}.ui-tooltip-tipsy .ui-tooltip-icon{border-color:#222;text-shadow:none}.ui-tooltip-tipsy .ui-tooltip-titlebar .ui-state-hover{border-color:#303030}.ui-tooltip-tipped{border:3px solid #959FA9;-moz-border-radius:3px;-webkit-border-radius:3px;border-radius:3px;background-color:#F9F9F9;color:#454545;font-weight:400;font-family:serif}.ui-tooltip-tipped .ui-tooltip-titlebar{border-bottom-width:0;color:#fff;background:#3A79B8;background-image:-webkit-gradient(linear,left top,left bottom,from( #3A79B8),to( #2E629D));background-image:-webkit-linear-gradient(top, #3A79B8, #2E629D);background-image:-moz-linear-gradient(top, #3A79B8, #2E629D);background-image:-ms-linear-gradient(top, #3A79B8, #2E629D);background-image:-o-linear-gradient(top, #3A79B8, #2E629D);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#3A79B8, endColorstr=#2E629D);-ms-filter:"progid:DXImageTransform.Microsoft.gradient(startColorstr=#3A79B8, endColorstr=#2E629D)"}.ui-tooltip-tipped .ui-tooltip-icon{border:2px solid #285589;background:#285589}.ui-tooltip-tipped .ui-tooltip-icon .ui-icon{background-color:#FBFBFB;color:#555}.ui-tooltip-bootstrap{font-size:13px;line-height:18px;color:#333;background-color:#fff;border:1px solid #ccc;border:1px solid rgba(0,0,0,.2);*border-right-width:2px;*border-bottom-width:2px;-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px;-webkit-box-shadow:0 5px 10px rgba(0,0,0,.2);-moz-box-shadow:0 5px 10px rgba(0,0,0,.2);box-shadow:0 5px 10px rgba(0,0,0,.2);-webkit-background-clip:padding-box;-moz-background-clip:padding;background-clip:padding-box}.ui-tooltip-bootstrap .ui-tooltip-titlebar{font-size:18px;line-height:22px;border-bottom:1px solid #ccc;background-color:transparent}.ui-tooltip-bootstrap .ui-tooltip-titlebar .ui-state-default{right:9px;top:49%;border-style:none}.ui-tooltip-bootstrap .ui-tooltip-icon{background:#fff}.ui-tooltip-bootstrap .ui-tooltip-icon .ui-icon{width:auto;height:auto;float:right;font-size:20px;font-weight:700;line-height:18px;color:#000;text-shadow:0 1px 0 #fff;opacity:.2;filter:alpha(opacity=20)}.ui-tooltip-bootstrap .ui-tooltip-icon .ui-icon:hover{color:#000;text-decoration:none;cursor:pointer;opacity:.4;filter:alpha(opacity=40)}.ui-tooltip:not(.ie9haxors) div.ui-tooltip-content,.ui-tooltip:not(.ie9haxors) div.ui-tooltip-titlebar{filter:none;-ms-filter:none}.ui-tooltip .ui-tooltip-tip{margin:0 auto;overflow:hidden;z-index:10}.ui-tooltip .ui-tooltip-tip,.ui-tooltip .ui-tooltip-tip .qtip-vml{position:absolute;line-height:.1px!important;font-size:.1px!important;color:#123456;background:transparent;border:0 dashed transparent}.ui-tooltip .ui-tooltip-tip canvas{top:0;left:0}.ui-tooltip .ui-tooltip-tip .qtip-vml{behavior:url(#default#VML);display:inline-block;visibility:visible}#qtip-overlay{position:fixed;left:-10000em;top:-10000em}#qtip-overlay.blurs{cursor:pointer}#qtip-overlay div{position:absolute;left:0;top:0;width:100%;height:100%;background-color:#000;opacity:.7;filter:alpha(opacity=70);-ms-filter:"alpha(Opacity=70)"}
		</style>
		<?php
	}

	function admin_head(){
	
		// Hack PoP Plug-in: replaced:
		/*background:url(<?php echo plugins_url('includes/images/cross.png',__FILE__); ?> */
		//with
		/*background:url(includes/images/cross.png)*/
		// this is because when minifying the file, it crashed with Firefox and it lost all styles	
		?>
		<style type="text/css">
			/* Event Custom Emails */
			div.emp-cet h4 { font-size:1.2em; }
			div.emp-cet h4 a, div.emp-cet h5 a { color:#333; text-decoration:none; }
			div.emp-cet-group { margin-bottom:30px; }
			div.emp-cet-group h5 { font-size:1.1em; }
			div.emp-cet-subgroup { margin-bottom:20px; }
			div.emp-cet .emp-enabled { color:green; }
			div.emp-cet .emp-disabled { color:red; }
			div.emp-cet .emp-default { color:#999; }			
			div.emp-cet .emp-cet-vals { margin: 10px 10px 20px; }
			div.emp-cet .emp-cet-val { margin-bottom:10px; }	
			div.emp-cet .emp-cet-vals input { width:100%; }
			div.emp-cet .emp-cet-vals textarea { width:100%; height:200px; }
			/* Custom Form Editor CSS */
			#em-booking-form-editor form { display:inline; }
			#em-attendee-form-editor form { display:inline; }
				/* structure */
				.em-form-custom > div { max-width:810px; border:1px solid #ccc; padding:10px 0px 0px; }
				.em-form-custom .booking-custom-head { font-weight:bold; }
				.em-form-custom .booking-custom > div, .booking-custom > ul {  padding:10px; }
				.em-form-custom .booking-custom-item { clear:left; border-top:1px solid #dedede; padding-top:10px; overflow:visible; }
				/* cols/fields */
				.em-form-custom .bc-col { float:left; width:140px; text-align:left; margin:0px 20px 0px 0px; }
				.em-form-custom .bc-col-type select { width:100%; }
				.em-form-custom .bc-col-required { width:50px; text-align:center; }
				.em-form-custom .bc-col-sort { margin-left:10px; width:25px; height:25px; background:url('includes/images/cross.png') 0px 0px no-repeat; cursor:move; }
				.em-form-custom .booking-custom-head .bc-col-sort { background:none; }
				.em-form-custom .booking-custom-types { clear:left; }
				.em-form-custom .booking-custom-types .bct-options { clear:left; margin-top:50px; }
				.em-form-custom .booking-custom-types .bct-field { clear:left; margin-top:10px; }
				/* option structure */
				.em-form-custom .bct-options { padding:0px 20px; }
				.em-form-custom .bct-field .bct-label { float:left; width:120px; }
				.em-form-custom .bct-field .bct-input { margin:0px 0px 10px 130px; }
				.em-form-custom .bct-field .bct-input input, .bct-field .bct-input textarea { display:block; }
				/* Sorting */
				.em-form-custom .booking-custom { list-style-type: none; margin: 0; padding: 0; }
				.em-form-custom .bc-highlight { height:45px; line-height:35px; border:1px solid #cdcdcd; background:#efefef; }
				/* Booking Form Attendee Admin */
				.em-attendee-details { border-bottom: 2px solid #dedede; margin:5px; padding-bottom:5px; }
				.em-attendee-form-admin .em-attendee-fields, .em-attendee-form-admin .em-booking-single-info  { padding:5px 10px; width:auto; text-align:left; border-top: 1px dotted #dedede; }
				.em-attendee-form-admin h4 { font-size:14px !important; margin:5px 0px 10px; }
				.em-attendee-form-admin input.input, .em-attendee-form-admin textarea { width:250px; }
				.em-attendee-form-admin .em-attendee-fields p { margin:0px 0px 4px 0px; }
				.em-attendee-form-admin label  { display:inline-block; width:100px; }
				/* Duplicate! */
				 /* Checkout Form CSS */
				table.em-cart-table { border-collapse:collapse; border-spacing:0; width:100%; margin:10px 0px 20px; }
				 table.em-cart-table th, table.em-cart-table td { border:1px solid #efefef; padding:10px; }
				 table.em-cart-table th { background-color:#eee; text-align:center; }
				 table.em-cart-table td { background-color:#fff; }
				 table.em-cart-table th.em-cart-title-event { text-align:left; }
				 table.em-cart-table tbody.em-cart-totals th { text-align:right; padding-right:20px; }
				 table.em-cart-table .em-cart-table-details-hide { display:none; visibility:none; }
				 table.em-cart-table .em-cart-table-event-details { display:none; visibility:none; }
				 .em-cart-table-event-summary .em-cart-table-event-title { font-weight:bold; }
				 .em-cart-table-event-summary .em-cart-table-spaces { text-align:center; }
				 .em-cart-table-event-summary .em-cart-table-price, tbody.em-cart-totals td { text-align:right; }
				 .em-cart-table-event-details .em-cart-table-ticket { font-weight:bold; }
				 .em-cart-info .em-cart-info-booking-title { font-weight:bold; }
				 .em-cart-info .em-cart-info-title { text-decoration:underline; }
				 .em-cart-info .em-cart-info-values label { font-style:italic; display: inline-block; width:150px; }				
		</style>
		<?php
	}

	function em_install_warning(){
		?>
		<div class="error"><p><?php _e('Please make sure you install Events Manager as well. You can search and install this plugin from your plugin installer or download it <a href="http://wordpress.org/extend/plugins/events-manager/">here</a>.','em-pro'); ?> <em><?php _e('Only admins see this message.','em-pro'); ?></em></p></div>
		<?php
	}

	function em_version_warning(){
		?>
		<div class="error"><p><?php _e('Please make sure you have the <a href="http://wordpress.org/extend/plugins/events-manager/">latest version</a> of Events Manager installed, as this may prevent Pro from functioning properly.','em-pro'); ?> <em><?php _e('Only admins see this message.','em-pro'); ?></em></p></div>
		<?php
	}
	
	static function log($log_text, $log_name = 'general', $force_logging = false){
		if( get_option('dbem_enable_logging') || $force_logging ){
			if( !class_exists('EMP_Logs') ){
				include_once('emp-logs.php');
			}
			return EMP_Logs::log($log_text, $log_name);
		}
		return false;
	}

}
//Add translation
load_plugin_textdomain('em-pro', false, dirname( plugin_basename( __FILE__ ) ).'/langs');

//Include admin file if needed
if(is_admin()){
	//include_once('em-pro-admin.php');
	include_once('emp-updates.php'); //update manager
}
// Start plugin
global $EM_Pro;
$EM_Pro = new EM_Pro();

/* Creating the wp_events table to store event data*/
function emp_activate() {
	global $wp_rewrite;
   	$wp_rewrite->flush_rules();
}
register_activation_hook( __FILE__,'emp_activate');

/**
 * Handle MS blog deletions
 * @param int $blog_id
 */
function emp_delete_blog( $blog_id ){
	global $wpdb;
	$prefix = $wpdb->get_blog_prefix($blog_id);
	$wpdb->query('DROP TABLE '.$prefix.'em_transactions');
	$wpdb->query('DROP TABLE '.$prefix.'em_coupons');
	$wpdb->query('DROP TABLE '.$prefix.'em_email_queue');
	$wpdb->query('DROP TABLE '.$prefix.'em_bookings_relationships');
}
add_action('delete_blog','emp_delete_blog');

//cron functions - ran here since functions aren't loaded, scheduling done by gateways and other modules
/**
 * Adds a schedule according to EM
 * @param array $shcehules
 * @return array
 */
function emp_cron_schedules($schedules){
	$schedules['em_minute'] = array(
		'interval' => 60,
		'display' => 'Every Minute'
	);
	return $schedules;
}
add_filter('cron_schedules','emp_cron_schedules',10,1);

/**
 * Copied from em_locate_template. Same code, but looks up the folder events-manager-pro in your theme.
 * @param string $template_name
 * @param boolean $load
 * @uses locate_template()
 * @return string
 */
function emp_locate_template( $template_name, $load=false, $args = array() ) {
	//First we check if there are overriding tempates in the child or parent theme
	$located = locate_template(array('plugins/events-manager-pro/'.$template_name));
	if( !$located ){
		$dir_location = plugin_dir_path(__FILE__);
		if ( file_exists( $dir_location.'templates/'.$template_name) ) {
			$located = $dir_location.'templates/'.$template_name;
		}
	}
	$located = apply_filters('emp_locate_template', $located, $template_name, $load, $args);
	if( $located && $load ){
		if( is_array($args) ) extract($args);
		include($located);
	}
	return $located;
}
?>