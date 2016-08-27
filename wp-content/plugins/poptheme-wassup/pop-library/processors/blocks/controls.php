<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_PAGECONTROL', PoP_ServerUtils::get_template_definition('block-pagecontrol'));
define ('GD_TEMPLATE_BLOCK_PAGEWITHSIDECONTROL', PoP_ServerUtils::get_template_definition('block-pagewithsidecontrol'));
define ('GD_TEMPLATE_BLOCK_SINGLECONTROL', PoP_ServerUtils::get_template_definition('block-singlecontrol'));
define ('GD_TEMPLATE_BLOCK_HOMECONTROL', PoP_ServerUtils::get_template_definition('block-homecontrol'));
define ('GD_TEMPLATE_BLOCK_AUTHORCONTROL', PoP_ServerUtils::get_template_definition('block-authorcontrol'));
define ('GD_TEMPLATE_BLOCK_TAGCONTROL', PoP_ServerUtils::get_template_definition('block-tagcontrol'));

define ('GD_TEMPLATE_BLOCK_QUICKVIEWPAGECONTROL', PoP_ServerUtils::get_template_definition('block-quickviewpagecontrol'));
define ('GD_TEMPLATE_BLOCK_QUICKVIEWPAGEWITHSIDECONTROL', PoP_ServerUtils::get_template_definition('block-quickviewpagewithsidecontrol'));
define ('GD_TEMPLATE_BLOCK_QUICKVIEWSINGLECONTROL', PoP_ServerUtils::get_template_definition('block-quickviewsinglecontrol'));
define ('GD_TEMPLATE_BLOCK_QUICKVIEWHOMECONTROL', PoP_ServerUtils::get_template_definition('block-quickviewhomecontrol'));
define ('GD_TEMPLATE_BLOCK_QUICKVIEWAUTHORCONTROL', PoP_ServerUtils::get_template_definition('block-quickviewauthorcontrol'));
define ('GD_TEMPLATE_BLOCK_QUICKVIEWTAGCONTROL', PoP_ServerUtils::get_template_definition('block-quickviewtagcontrol'));

define ('GD_TEMPLATE_BLOCK_PAGECONTROL_SINGLESIDEBAR', PoP_ServerUtils::get_template_definition('block-pagecontrol-singlesidebar'));
define ('GD_TEMPLATE_BLOCK_PAGECONTROL_AUTHORSIDEBAR', PoP_ServerUtils::get_template_definition('block-pagecontrol-authorsidebar'));
define ('GD_TEMPLATE_BLOCK_PAGECONTROL_PAGESIDEBAR', PoP_ServerUtils::get_template_definition('block-pagecontrol-pagesidebar'));

define ('GD_TEMPLATE_BLOCK_PAGECONTROL_ABOUT', PoP_ServerUtils::get_template_definition('block-pagecontrol-about'));
define ('GD_TEMPLATE_BLOCK_PAGECONTROL_WEBPOSTLINK_CREATE', PoP_ServerUtils::get_template_definition('block-pagecontrol-webpostlink-create'));
define ('GD_TEMPLATE_BLOCK_PAGECONTROL_HIGHLIGHT_CREATE', PoP_ServerUtils::get_template_definition('block-pagecontrol-highlight-create'));
define ('GD_TEMPLATE_BLOCK_PAGECONTROL_WEBPOST_CREATE', PoP_ServerUtils::get_template_definition('block-pagecontrol-webpost-create'));

class GD_Template_Processor_ControlBlocks extends GD_Template_Processor_ControlBlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_PAGECONTROL,
			GD_TEMPLATE_BLOCK_PAGEWITHSIDECONTROL,
			GD_TEMPLATE_BLOCK_SINGLECONTROL,
			GD_TEMPLATE_BLOCK_HOMECONTROL,
			GD_TEMPLATE_BLOCK_AUTHORCONTROL,
			GD_TEMPLATE_BLOCK_TAGCONTROL,
			GD_TEMPLATE_BLOCK_QUICKVIEWPAGECONTROL,
			GD_TEMPLATE_BLOCK_QUICKVIEWPAGEWITHSIDECONTROL,
			GD_TEMPLATE_BLOCK_QUICKVIEWSINGLECONTROL,
			GD_TEMPLATE_BLOCK_QUICKVIEWHOMECONTROL,
			GD_TEMPLATE_BLOCK_QUICKVIEWAUTHORCONTROL,
			GD_TEMPLATE_BLOCK_QUICKVIEWTAGCONTROL,
			GD_TEMPLATE_BLOCK_PAGECONTROL_SINGLESIDEBAR,
			GD_TEMPLATE_BLOCK_PAGECONTROL_AUTHORSIDEBAR,
			GD_TEMPLATE_BLOCK_PAGECONTROL_PAGESIDEBAR,
			GD_TEMPLATE_BLOCK_PAGECONTROL_ABOUT,
			GD_TEMPLATE_BLOCK_PAGECONTROL_WEBPOSTLINK_CREATE,
			GD_TEMPLATE_BLOCK_PAGECONTROL_HIGHLIGHT_CREATE,
			GD_TEMPLATE_BLOCK_PAGECONTROL_WEBPOST_CREATE,
		);
	}

	protected function get_controlgroup_top($template_id) {

		$controlgroups = array(
			GD_TEMPLATE_BLOCK_PAGECONTROL => GD_TEMPLATE_CONTROLGROUP_PAGEOPTIONS,
			GD_TEMPLATE_BLOCK_PAGEWITHSIDECONTROL => GD_TEMPLATE_CONTROLGROUP_PAGEWITHSIDEOPTIONS,
			GD_TEMPLATE_BLOCK_SINGLECONTROL => GD_TEMPLATE_CONTROLGROUP_SINGLEOPTIONS,
			GD_TEMPLATE_BLOCK_HOMECONTROL => GD_TEMPLATE_CONTROLGROUP_HOMEOPTIONS,
			GD_TEMPLATE_BLOCK_AUTHORCONTROL => GD_TEMPLATE_CONTROLGROUP_AUTHOROPTIONS,
			GD_TEMPLATE_BLOCK_TAGCONTROL => GD_TEMPLATE_CONTROLGROUP_TAGOPTIONS,
			// Comment Leo 16/08/2016: No need for the Quickview Sideinfo anymore
			// GD_TEMPLATE_BLOCK_QUICKVIEWPAGECONTROL => GD_TEMPLATE_CONTROLGROUP_QUICKVIEWPAGEOPTIONS,
			// GD_TEMPLATE_BLOCK_QUICKVIEWPAGEWITHSIDECONTROL => GD_TEMPLATE_CONTROLGROUP_QUICKVIEWPAGEWITHSIDEOPTIONS,
			// GD_TEMPLATE_BLOCK_QUICKVIEWSINGLECONTROL => GD_TEMPLATE_CONTROLGROUP_QUICKVIEWSINGLEOPTIONS,
			// GD_TEMPLATE_BLOCK_QUICKVIEWHOMECONTROL => GD_TEMPLATE_CONTROLGROUP_QUICKVIEWHOMEOPTIONS,
			// GD_TEMPLATE_BLOCK_QUICKVIEWAUTHORCONTROL => GD_TEMPLATE_CONTROLGROUP_QUICKVIEWAUTHOROPTIONS,
			// GD_TEMPLATE_BLOCK_QUICKVIEWTAGCONTROL => GD_TEMPLATE_CONTROLGROUP_QUICKVIEWTAGOPTIONS,
			GD_TEMPLATE_BLOCK_PAGECONTROL_SINGLESIDEBAR => GD_TEMPLATE_CONTROLGROUP_TOGGLESIDEINFO_BACK,
			GD_TEMPLATE_BLOCK_PAGECONTROL_AUTHORSIDEBAR => GD_TEMPLATE_CONTROLGROUP_TOGGLESIDEINFO_BACK,
			GD_TEMPLATE_BLOCK_PAGECONTROL_PAGESIDEBAR => GD_TEMPLATE_CONTROLGROUP_TOGGLESIDEINFO_BACK,
			GD_TEMPLATE_BLOCK_PAGECONTROL_ABOUT => GD_TEMPLATE_CONTROLGROUP_SINGLEOPTIONS,
			GD_TEMPLATE_BLOCK_PAGECONTROL_WEBPOSTLINK_CREATE => GD_TEMPLATE_CONTROLGROUP_PAGEOPTIONS,
			GD_TEMPLATE_BLOCK_PAGECONTROL_HIGHLIGHT_CREATE => GD_TEMPLATE_CONTROLGROUP_PAGEOPTIONS,
			GD_TEMPLATE_BLOCK_PAGECONTROL_WEBPOST_CREATE => GD_TEMPLATE_CONTROLGROUP_PAGEOPTIONS,
		);
		if ($controlgroup = $controlgroups[$template_id]) {

			return $controlgroup;
		}

		return parent::get_controlgroup_top($template_id);
	}

	protected function get_blocksections_classes($template_id) {

		$ret = parent::get_blocksections_classes($template_id);
		
		switch ($template_id) {
			
			case GD_TEMPLATE_BLOCK_SINGLECONTROL:
				
				$ret['controlgroup-bottom'] = 'pull-right';
				break;
		}
		
		return $ret;
	}

	protected function get_controlgroup_bottom($template_id) {

		$controlgroups = array(
			GD_TEMPLATE_BLOCK_SINGLECONTROL => GD_TEMPLATE_CONTROLGROUP_SINGLEOPTIONSBOTTOM,
		);
		if ($controlgroup = $controlgroups[$template_id]) {

			return $controlgroup;
		}

		return parent::get_controlgroup_bottom($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_PAGECONTROL:
			case GD_TEMPLATE_BLOCK_SINGLECONTROL:
			case GD_TEMPLATE_BLOCK_HOMECONTROL:
			case GD_TEMPLATE_BLOCK_AUTHORCONTROL:
			case GD_TEMPLATE_BLOCK_TAGCONTROL:
			case GD_TEMPLATE_BLOCK_QUICKVIEWPAGECONTROL:
			case GD_TEMPLATE_BLOCK_QUICKVIEWSINGLECONTROL:
			case GD_TEMPLATE_BLOCK_QUICKVIEWHOMECONTROL:
			case GD_TEMPLATE_BLOCK_QUICKVIEWAUTHORCONTROL:
			case GD_TEMPLATE_BLOCK_QUICKVIEWTAGCONTROL:
			case GD_TEMPLATE_BLOCK_PAGECONTROL_SINGLESIDEBAR:
			case GD_TEMPLATE_BLOCK_PAGECONTROL_AUTHORSIDEBAR:
			case GD_TEMPLATE_BLOCK_PAGECONTROL_PAGESIDEBAR:

				$this->append_att($template_id, $atts, 'class', 'controlblock');
				break;
		}
		
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_HOMECONTROL:

				$this->append_att($template_id, $atts, 'class', 'block-homecontrol');
				break;
		}
		
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_ControlBlocks();