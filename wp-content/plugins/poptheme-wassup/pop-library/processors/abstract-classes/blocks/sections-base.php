<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/


class GD_Template_Processor_SectionBlocksBase extends GD_Template_Processor_BlocksBase {
	
	protected function get_block_inner_templates($template_id) {

		global $gd_template_processor_manager;

		$ret = parent::get_block_inner_templates($template_id);

		if ($sectionfilter_template = $this->get_sectionfilter_template($template_id)) {

			$ret[] = $sectionfilter_template;
		}
				
		if ($block_inner_template = $this->get_block_inner_template($template_id)) {

			$ret[] = $block_inner_template;
		}

		return $ret;
	}

	protected function get_sectionfilter_template($template_id) {

		return null;
	}

	protected function get_block_inner_template($template_id) {

		return null;
	}

	protected function block_requires_user_state($template_id, $atts) {

		return false;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		
		if ($this->block_requires_user_state($template_id, $atts)) {

			// Only reload/destroy if these are main blocks
			if ($this->get_att($template_id, $atts, 'is-mainblock')) {

				$this->add_jsmethod($ret, 'destroyPageOnUserLoggedOut');
				$this->add_jsmethod($ret, 'refetchBlockOnUserLoggedIn');
			}
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {		

		if ($sectionfilter = $this->get_sectionfilter_template($template_id)) {

			// Class needed to push the "Loading" status a tiny bit down, so it doesn't show on top of the sectionfilter
			$this->append_att($template_id, $atts, 'class', 'withsectionfilter');

			// Check if the filter needs to be hidden (eg: GetPoP homepage)
			if ($this->get_att($template_id, $atts, 'hide-sectionfilter')) {

				$this->append_att($sectionfilter, $atts, 'class', 'hidden');
			}
		}

		return parent::init_atts($template_id, $atts);
	}
}
