<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_SidebarBlockGroupsBase extends GD_Template_Processor_ListBlockGroupsBase {

	protected function get_inner_blocks($template_id) {

		return array();
	}

	protected function get_screen($template_id) {

		return null;
	}

	protected function get_screengroup($template_id) {

		return null;
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		// Add the corresponding blocks
		if ($blocks = $this->get_inner_blocks($template_id)) {

			$ret = array_merge(
				$ret,
				$blocks
			);
		}
		
		// Allow to add the Trending Tags/Events Calendar at the bottom of the sideinfo
		if ($blocks = apply_filters(
				'GD_Template_Processor_SidebarBlockGroupsBase:blocks',
				array(),
				$this->get_screengroup($template_id),
				$this->get_screen($template_id),
				$template_id
			)) {
			$ret = array_merge(
				$ret,
				$blocks
			);
		}

		return $ret;
	}

	function get_blockgroup_blockgroups($template_id) {

		$ret = parent::get_blockgroup_blockgroups($template_id);

		// Allow to add the customized Blog block in GetPoP
		if ($blockgroups = apply_filters(
				'GD_Template_Processor_SidebarBlockGroupsBase:blockgroups',
				array(),
				$this->get_screengroup($template_id),
				$this->get_screen($template_id),
				$template_id
			)) {
			$ret = array_merge(
				$ret,
				$blockgroups
			);
		}

		return $ret;
	}
}