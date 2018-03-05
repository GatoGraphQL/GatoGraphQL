<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Gravity Forms plugin Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_GFPoPGenericForms_GFHooks {

	function __construct() {
    
		add_filter(
			'GD_DataLoad_ActionExecuter_GravityForms:fieldnames',
			array($this, 'get_fieldnames'),
			10,
			2
		);
	}

	function get_fieldnames($fieldnames, $form_id) {

    	if ($form_id == PoP_GFPoPGenericForms_GFHelpers::get_contactus_form_id()) {				
			
			$fieldnames = PoP_GFPoPGenericForms_GFHelpers::get_contactus_form_field_names();
		}
		elseif ($form_id == PoP_GFPoPGenericForms_GFHelpers::get_contactuser_form_id()) {				
			
			$fieldnames = PoP_GFPoPGenericForms_GFHelpers::get_contactuser_form_field_names();
		}
		elseif ($form_id == PoP_GFPoPGenericForms_GFHelpers::get_sharebyemail_form_id()) {				
			
			$fieldnames = PoP_GFPoPGenericForms_GFHelpers::get_sharebyemail_form_field_names();
		}
		elseif ($form_id == PoP_GFPoPGenericForms_GFHelpers::get_volunteer_form_id()) {				
			
			$fieldnames = PoP_GFPoPGenericForms_GFHelpers::get_volunteer_form_field_names();
		}
		elseif ($form_id == PoP_GFPoPGenericForms_GFHelpers::get_flag_form_id()) {				
			
			$fieldnames = PoP_GFPoPGenericForms_GFHelpers::get_flag_form_field_names();
		}
		
		return $fieldnames; 
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_GFPoPGenericForms_GFHooks();