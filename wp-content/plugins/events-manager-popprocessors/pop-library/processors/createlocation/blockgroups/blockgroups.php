<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_CREATELOCATION', PoP_ServerUtils::get_template_definition('blockgroup-createlocation'));

class GD_Template_Processor_CreateLocationBlockGroups extends GD_Template_Processor_ListBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_CREATELOCATION,
		);
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_CREATELOCATION:

				$ret[] = GD_TEMPLATE_BLOCK_CREATELOCATION;
				$ret[] = GD_TEMPLATE_BLOCKDATA_CREATELOCATION;
				break;
		}
		
		return $ret;
	}

	// function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {
		
	// 	switch ($blockgroup) {

	// 		case GD_TEMPLATE_BLOCKGROUP_CREATELOCATION:

	// 			if ($blockgroup_block == GD_TEMPLATE_BLOCK_CREATELOCATION) {

	// 				// Hide the Title
	// 				$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', '');

	// 				// Make the block close the modal when the execution was successful
	// 				$this->merge_block_jsmethod_att($blockgroup_block, $blockgroup_block_atts, array('maybeCloseLocationModal'));
	// 			}
	// 			break;
	// 	}

	// 	return parent::init_atts_blockgroup_block($blockgroup, $blockgroup_block, $blockgroup_block_atts, $blockgroup_atts);
	// }

	function get_dataload_source($template_id, $atts) {

		// Needed so it can be intercepted
		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_CREATELOCATION:

				return get_permalink(POP_EM_POPPROCESSORS_PAGE_ADDLOCATION);
		}

		return parent::get_dataload_source($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CreateLocationBlockGroups();
