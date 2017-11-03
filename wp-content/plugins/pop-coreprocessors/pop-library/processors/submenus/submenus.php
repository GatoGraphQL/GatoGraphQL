<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SUBMENU_ACCOUNT', PoP_TemplateIDUtils::get_template_definition('submenu-account'));

class GD_Template_Processor_SubMenus extends GD_Template_Processor_SubMenusBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SUBMENU_ACCOUNT
		);
	}

	function get_blockunititem_class($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_SUBMENU_ACCOUNT:

				// Using btn-link instead of btn-success because the 'active' cannot be calculated on runtime, and btn-link does not paint the active in any different way
				return 'btn btn-link';
		}

		return parent::get_blockunititem_class($template_id);
	}
	function get_blockunititem_xs_class($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_SUBMENU_ACCOUNT:

				// Using btn-link instead of btn-success because the 'active' cannot be calculated on runtime, and btn-link does not paint the active in any different way
				return 'btn btn-default btn-block';
		}

		return parent::get_blockunititem_class($template_id);
	}
	function get_blockunititem_dropdown_class($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_SUBMENU_ACCOUNT:

				return 'btn-link';
		}

		return parent::get_blockunititem_dropdown_class($template_id);
	}

	function get_blockunititems($template_id, $atts) {

		global $gd_template_settingsmanager;

		switch ($template_id) {

			case GD_TEMPLATE_SUBMENU_ACCOUNT:

				$ret = array(
					GD_TEMPLATE_BLOCK_LOGIN => array(),
				);

				// Allow for the Create Profiles links to be added by User Role Editor
				return apply_filters('GD_Template_Processor_SubMenus:get_blockgroupitems', $ret);
		}

		return parent::get_blockunititems($template_id, $atts);
	}

	// function get_blockunititem_title($template_id, $blockunit) {

	// 	global $gd_template_settingsmanager;
	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_SUBMENU_ACCOUNT:

	// 			$page_id = $gd_template_settingsmanager->get_blockgroup_page($blockunit);
	// 			return get_the_title($page_id);
	// 	}
	
	// 	return parent::get_blockunititem_title($template_id, $blockunit);
	// }

	function get_blockunititem_url($template_id, $blockunit) {

		global $gd_template_settingsmanager;
		switch ($template_id) {

			case GD_TEMPLATE_SUBMENU_ACCOUNT:

				$page_id = $gd_template_settingsmanager->get_block_page($blockunit);
				return get_permalink($page_id);
		}
	
		return parent::get_blockunititem_url($template_id, $blockunit);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		switch ($template_id) {
		
			case GD_TEMPLATE_SUBMENU_ACCOUNT:

				$this->add_jsmethod($ret, 'addDomainClass');
				break;
		}
		
		return $ret;
	}
	function get_js_setting($template_id, $atts) {

		$ret = parent::get_js_setting($template_id, $atts);

		switch ($template_id) {
		
			case GD_TEMPLATE_SUBMENU_ACCOUNT:

				// For function addDomainClass
				$ret['prefix'] = 'visible-notloggedin-';
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_SUBMENU_ACCOUNT:

				$this->append_att($template_id, $atts, 'class', 'visible-notloggedin');
				break;
		}
	
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_SubMenus();
