<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/


/**---------------------------------------------------------------------------------------------------------------
 * Override with custom blocks
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_Template_Processor_CustomBlockGroups:blocks:whoweare', 'agendaurbana_custom_blocks_whoweare', 100);
function agendaurbana_custom_blocks_whoweare($blocks) {

	return array(
		GD_TEMPLATE_BLOCK_WHOWEARE_CORE_SCROLL,
		GD_TEMPLATE_BLOCK_WHOWEARE_VOLUNTEERS_SCROLL,
	);
}