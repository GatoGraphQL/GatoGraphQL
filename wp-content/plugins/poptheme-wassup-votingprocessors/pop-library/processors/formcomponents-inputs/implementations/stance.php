<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD FormInput Status
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FormInput_Stance extends GD_FormInput_Select {
	
	function get_all_values($label = null) {

		$values = parent::get_all_values($label);
		
		$values = array_merge(	
			$values, 
			array(	
				'pro' => '<i class="fa fa-fw fa-thumbs-o-up"></i>'.__('Pro TPP', 'poptheme-wassup-votingprocessors'),
				'neutral' => '<i class="fa fa-fw fa-hand-peace-o"></i>'.__('Neutral', 'poptheme-wassup-votingprocessors'),
				'against' => '<i class="fa fa-fw fa-thumbs-o-down"></i>'.__('Against TPP', 'poptheme-wassup-votingprocessors'),
			)
		);		
		
		return $values;
	}	
	
	// function get_default_value($conf) {
	
	// 	return 'neutral';
	// }
}
