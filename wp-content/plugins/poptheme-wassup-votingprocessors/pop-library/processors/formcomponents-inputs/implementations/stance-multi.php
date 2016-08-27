<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput Status
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FormInput_MultiStance extends GD_FormInput_MultiSelect {
	
	function get_all_values($label = null) {

		$values = parent::get_all_values($label);
		
		$values[POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_PRO] = __('Pro', 'poptheme-wassup-votingprocessors');
		$values[POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_AGAINST] = __('Against', 'poptheme-wassup-votingprocessors');
		$values[POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_NEUTRAL] = __('Neutral', 'poptheme-wassup-votingprocessors');

		// Allow to override these values: Allow TPPDebate to change "Pro" to "Pro TPP", etc
		$values = apply_filters('GD_FormInput_MultiStance:values', $values);
		
		return $values;
	}	
}
