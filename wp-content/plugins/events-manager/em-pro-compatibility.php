<?php
//in the event the Pro add-on is installed but fails to meet the minimum EM_PRO_MIN_VERSION, this file will be executed in case extra actions are needed
define('EM_COMPAT_MESSAGE_BE_GONE',"Only Administrators see this message. To hide critical messages and prevent this safety measure, you can add <code>define('EMP_DISABLE_CRITICAL_WARNINGS', true);</code> as a new line in your wp-config.php file."); 
if( EMP_VERSION < 2.377 && (!defined('EMP_2376_FIXED') || !EMP_2376_FIXED) ){
	//we changed some function scopes to static, so Pro must be updated or modified to prevent fatal errors and should not be activated
	//we're deactivating Pro until that happens
	global $EM_Pro;
	remove_action( 'plugins_loaded', array(&$EM_Pro, 'init') );
		
	function em_empro_lt_2376_notice(){
		?>
		<div class="error">
			<p>Due to some inevitable changes to some code in Events Manager, it is necessary to use Pro 2.3.7.7 or later. We have disabled the Pro plugin as a safety precaution.</p>
			<p><a href="http://wp-events-plugin.com/blog/2014/04/15/important-changes-to-5-5-3-and-pro-2-3-8-versions/">Click here for detailed information about this change</a>, which includes some simple instructions for fixing older versions of Pro without requiring an update. </p>
			<p><?php echo EM_COMPAT_MESSAGE_BE_GONE; ?></p>
		</div>
		<?php
	}
	if( is_super_admin() ){
		add_action ( 'admin_notices', 'em_empro_lt_2376_notice', 100 );
		add_action ( 'network_admin_notices', 'em_empro_lt_2376_notice', 100 );
	}
}