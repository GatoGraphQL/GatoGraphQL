<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ListBlockGroupsBase extends GD_Template_Processor_BlockGroupsBase {

	protected function get_block_extension_templates($template_id) {

		$ret = parent::get_block_extension_templates($template_id);
		$ret[] = GD_TEMPLATESOURCE_BLOCKGROUP_BLOCKUNITS;		
		return $ret;
	}
	
	protected function get_initjs_blockbranches($template_id, $atts) {

		$ret = parent::get_initjs_blockbranches($template_id, $atts);

		if ($activeblock_selector = $this->get_activeblock_selector($template_id, $atts)) {

			$ret[] = $activeblock_selector;
		}

		return $ret;
	}

	function get_activeblock_selector($template_id, $atts) {

		$id = $atts['block-id'];
		return '#'.$id.' > div.blocksection-extensions > div.pop-block';
		// return '#'.$id.' > div.blocksection-extensions > div.blockwrapper > div.pop-block';
	}

	// function get_blockwrapper_class($template_id) {

	// 	return '';
	// }
	// function get_blockwrapper_inner_class($template_id) {

	// 	return '';
	// }

	// function get_template_configuration($template_id, $atts) {

	// 	$ret = parent::get_template_configuration($template_id, $atts);

	// 	if ($blockwrapper_class = $this->get_blockwrapper_class($template_id)) {
	// 		$ret['classes']['blockwrapper'] = $blockwrapper_class;
	// 	}

	// 	// if ($blockwrapper_outer_class = $this->get_blockwrapper_outer_class($template_id)) {
	// 	// 	$ret['classes']['blockwrapper-outer'] = $blockwrapper_outer_class;
	// 	// }
	// 	// if ($blockwrapper_inner_class = $this->get_blockwrapper_inner_class($template_id)) {
	// 	// 	$ret['classes']['blockwrapper-inner'] = $blockwrapper_inner_class;
	// 	// }

	// 	return $ret;
	// }
}
