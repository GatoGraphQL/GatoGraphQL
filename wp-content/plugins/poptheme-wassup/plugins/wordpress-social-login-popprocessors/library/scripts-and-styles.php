<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Scripts and styles
 *
 * ---------------------------------------------------------------------------------------------------------------*/


/**---------------------------------------------------------------------------------------------------------------
 * Logged in classes: they depend on the domain, so they are added through PHP, not in the .css anymore
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('get_loggedin_domain_styles_placeholder', 'get_wassup_wsl_loggedin_domain_styles_placeholder');
function get_wassup_wsl_loggedin_domain_styles_placeholder($placeholder) {

	$placeholder .= 
		'
			'./* WSL Users: do not show "Change Password" link */'
			body.loggedin-%1$s.wsluser-%1$s .hidden-wsluser-%1$s {
				display: none;
			}
		';
	return $placeholder;
}