<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_MessageBlocksBase extends GD_Template_Processor_BlocksBase {

	function get_cat($template_id) {

		return null;
	}

	protected function get_dataload_query_args($template_id, $atts) {

		$ret = parent::get_dataload_query_args($template_id, $atts);

		// If no sticky posts, then make sure we're bringing no results
		$sticky = get_option( 'sticky_posts' );
		if (!$sticky) {
			// $sticky = array('-1');
			$ret['load'] = false;
		}

		$ret['cat'] = $this->get_cat($template_id);
		// $ret['limit'] = 1;
		$ret['include'] = $sticky;

		return $ret;
	}

	function get_dataloader($template_id) {

		return GD_DATALOADER_POSTLIST;
	}
	
	// function init_atts($template_id, &$atts) {
	
	// 	$layout = $this->get_layout($template_id);
	// 	$this->add_att($layout, $atts, 'layout-inner', GD_TEMPLATE_LAYOUTPOST_SPEECHBUBBLE);
			
	// 	return parent::init_atts($template_id, $atts);
	// }
}