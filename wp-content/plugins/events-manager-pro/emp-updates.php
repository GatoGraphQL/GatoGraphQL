<?php
define( 'EM_PRO_ALT_API', 'http://api.wp-events-plugin.com/pro/' );
define( 'EM_PRO_ALT_API_2', 'http://api.wp-events-plugin.com/pro/' );
class EM_Updates {
	function init(){
		// For testing purpose, the site transient will be reset on each page load
		//add_action( 'init', array('EM_Updates','delete_transient') );
		// Hook into the plugin update check
		add_filter('pre_set_site_transient_update_plugins', array('EM_Updates','check'));
		// Hook into the plugin details screen
		add_filter('plugins_api', array('EM_Updates','info'), 10, 3);
		//echo "<pre>"; print_r(get_option('_site_transient_update_plugins')); echo "</pre>";
		if( is_admin() ){
			if( is_multisite() ){
				add_action('network_admin_notices', array('EM_Updates','admin_notices'));
				add_action('em_ms_options_page_footer', array('EM_Updates','admin_options'));
			}else{
				add_action('admin_notices', array('EM_Updates','admin_notices'));		
				add_action('em_options_page_footer', array('EM_Updates','admin_options'));		
			}
			add_action('admin_init', array('EM_Updates','admin_options_save')); //before normal options are saved  	 		
		}
		add_action( 'in_plugin_update_message-events-manager-pro/events-manager-pro.php',  array('EM_Updates','update_notices'));
	}
	
	/**
	 * Add an extra update message to the update plugin notification. Thanks BP!
	 */
	function update_notices() {
		if( !self::check_api_key()){
			$admin_url = ( is_multisite() ) ? network_admin_url('admin.php?page=events-manager-options'):admin_url('edit.php?post_type='.EM_POST_TYPE_EVENT.'&page=events-manager-options');
			echo '<p style="font-style:italic; border-top: 1px solid #ddd; padding-top: 3px">'.sprintf(__('Please enter a valid API key in your <a href="%s">settings page</a>.','em-pro'), $admin_url).'</p>';
		}
	}
	
	function admin_notices(){
		if( is_super_admin() && !empty($_REQUEST['page']) && ('events-manager-options' == $_REQUEST['page']) ){
			if( !self::check_api_key() ){
				?>
				<div id="message" class="updated">
					<p><?php echo sprintf(__('To access automatic updates, you must update your <a href="%s">Membership Key</a> for Events Mananager Pro <a href="#pro-api">here</a>. Only admins see this message.','em-pro'), 'http://wp-events-plugin.com/wp-admin/profile.php'); ?></p>
				</div>
				<?php
			}
			if( defined('EMP_DEV_UPDATES') && EMP_DEV_UPDATES ){
				?>
				<div id="message" class="updated">
					<p><?php echo sprintf(__('Dev Mode active: Just a friendly reminder that you have added %s to your wp-config.php file. Only admins see this message, and it will go away when you remove that line.','em-pro'),'<code>define(\'EMP_DEV_UPDATES\',true);</code>'); ?></p>
				</div>
				<?php
			}
		}
	}
	
	function admin_options_save(){
		/*
		 * Here's the idea, we have an array of all options that need super admin approval if in multi-site mode
		 * since options are only updated here, its one place fit all
		 */
		if( is_super_admin() && !empty($_POST['em-submitted']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'events-manager-options') ){
			//Build the array of options here
			$request_api_key = !empty($_REQUEST['dbem_pro_api_key']) ? $_REQUEST['dbem_pro_api_key']:'';
			if( $request_api_key != get_site_option('dbem_pro_api_key') || !self::check_api_key()){
				//update the option here
				update_site_option('dbem_pro_api_key', $request_api_key);
				//save api key either way
				if( self::check_api_key(true) ){
					//force recheck of plugin updates, to refresh dl links
					delete_transient('update_plugins');
					delete_site_transient('update_plugins');
				}
			}
		}
	}
	
	function admin_options(){
		global $save_button;
		$api = !self::check_api_key();
		if( is_super_admin() ){
		?>
			<a name="pro-api"></a>
			<div  class="postbox " id="em-opt-pro-key" >
			<div class="handlediv" title="<?php __('Click to toggle', 'dbem'); ?>"><br /></div><h3 class='hndle'><span><?php _e ( 'Pro Membership Key', 'em-pro' ); ?> </span></h3>
			<div class="inside">
				<table class='form-table' <?php echo ( $api ) ? 'style="background-color:#ffece8;"':'' ?>>
					<?php
					em_options_input_text ( __( 'Pro Member Key', 'em-pro' ), 'dbem_pro_api_key', sprintf( __("Insert your Pro Member Key to access automatic updates you can get your membership key from <a href=\"%s\">here</a>.", 'em-pro'), 'http://wp-events-plugin.com/wp-admin/profile.php' ));
					?>
					<?php if( !self::check_api_key() && get_option('dbem_pro_api_key') != '' ):?>
						<?php
						$response = get_site_transient('dbem_pro_api_key_check');
						if( $api ){
							?>
							<tr>
								<td colspan="2">
									<b>Returned Data:</b> 
									<?php print_r($response); ?>
								</td>
							</tr>
							<?php
						}
						?>
					<?php endif; ?>
				</table>
				<?php echo $save_button; ?>
			</div> <!-- . inside -->
			</div> <!-- .postbox -->
		<?php
		}
	}
	
	function delete_transient() {
		delete_site_transient( 'update_plugins' );
	}

	// Send a request to the alternative API, return an object
	function request( $args ) {
	
	    // Send request
	    $request = wp_remote_post( EM_PRO_ALT_API, array( 'body' => $args ) );
	    //print_r($request);
	    //try alt
		if( is_wp_error( $request ) or wp_remote_retrieve_response_code( $request ) != 200 ) {
	        // Request failed - try again
	    	$request = wp_remote_post( EM_PRO_ALT_API_2, array( 'body' => $args ) );
	    }
	    // Make sure the request was successful
	    if( is_wp_error( $request ) or wp_remote_retrieve_response_code( $request ) != 200 ) {
	        // Request failed
	        return $request;
	    }
	    
	    // Read server response, which should be an object
	    $response = unserialize( wp_remote_retrieve_body( $request ) );
	    if( is_object( $response ) ) {
	        return $response;
	    } else {
	        // Unexpected response
	        return $request;
	    }
	}
	
	function check_response($response){
	    // Make sure the request was successful
	    if( is_wp_error( $response ) ){ //wp erro object
	    	return false;
	    }elseif( is_object( $response ) ){ //we got our object - whatever it is
	    	 return true;
	    }else{
	        return false;
	    }
	}
	
	/* request types */
		
	function check_api_key($force = false){
		$result = get_site_transient('dbem_pro_api_key_check');
		if( is_object($result) && $result->valid && !$force ){
			return true;
		}elseif( defined('EM_DISABLE_API_REMINDER') ){
			return true;
		}else{
			//call to see if this key is valid
			if( !is_object($result) || $force ){
				//recreate result
				$result = new stdClass();
				$result->valid = false;			    
			    // POST data to send to your API
			    $args = array(
			        'action' => 'verify',
	        		'slug' => EMP_SLUG,
			    	'api_key' => trim(get_option('dbem_pro_api_key'))
			    );		
			    //request the latest dev version
			    if( (defined('EMP_DEV_UPDATES') && EMP_DEV_UPDATES) || get_option('dbem_pro_dev_updates') || get_option('em_check_dev_version') ){
			    	$args['dev_version'] = 1;
			    }	    
			    // Send request checking for an update
			    $response = self::request( $args );
			    // If response is false, don't alter the transient
			    if( self::check_response($response) ) {
			    	$result->valid = $response->result;
				    $result->checked = current_time('timestamp');    
				    $result->response = $response;
			   		set_site_transient('dbem_pro_api_key_check',$result,60*60*24);
			    }else{
			    	set_site_transient('dbem_pro_api_key_check',$response,60*60*24);
			    } 
			}
			return $result->valid;
		}
		return false;
	}

	//latest version
	function check( $transient ) {
	    // Check if the transient contains the 'checked' information
	    // If no, just return its value without hacking it
	    if( empty( $transient->checked ) )
	        return $transient;
	    
	    // The transient contains the 'checked' information
	    
	    // POST data to send to your API
	    $args = array(
	        'action' => 'check',
	        'slug' => EMP_SLUG,
	        'plugin_name' => EMP_SLUG,
	        'version' => $transient->checked[EMP_SLUG],
	    	'api_key' => trim(get_option('dbem_pro_api_key'))
	    );	
	    //request the latest dev version
	    if( (defined('EMP_DEV_UPDATES') && EMP_DEV_UPDATES) || get_option('dbem_pro_dev_updates') || get_option('em_check_dev_version') ){
	    	$args['dev_version'] = 1;
	    }	
	    
	    // Send request checking for an update
	    $response = self::request( $args );
	    
	    // If response is false, don't alter the transient
	    if( self::check_response($response) && version_compare($transient->checked[EMP_SLUG], $response->new_version) < 0) {
	        if( !self::check_api_key(true) ){
	        	$response->package = '';
	        }
	    	$transient->response[EMP_SLUG] = $response;
	    }
	    
	    return $transient;
	}
	
	//plugin info pane
	function info( $false, $action, $args ) {
	
	    // Check if this plugins API is about this plugin
	    if( $args->slug != 'events-manager-pro' ) {
	        return false;
	    }
	        
	    // POST data to send to your API
	    $args = array(
	        'action' => 'info',
	        'slug' => EMP_SLUG,
	        'plugin_name' => EMP_SLUG,
	        'version' => $transient->checked[EMP_SLUG],
	    );	
	    //request the latest dev version
	    if( (defined('EMP_DEV_UPDATES') && EMP_DEV_UPDATES) || get_option('dbem_pro_dev_updates') || get_option('em_check_dev_version') ){
	    	$args['dev_version'] = 1;
	    }	
	    
	    // Send request for detailed information
	    $response = self::request( $args );
	    $response = ( self::check_response($response) ) ? $response:false;
	
	    return $response;
	}
}
EM_Updates::init();
