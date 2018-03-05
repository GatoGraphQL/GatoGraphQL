<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_DROPDOWNBUTTONCONTROL_SHARE', PoP_TemplateIDUtils::get_template_definition('dropdownbuttoncontrol-share'));
define ('GD_TEMPLATE_DROPDOWNBUTTONCONTROL_FIXEDSHARE', PoP_TemplateIDUtils::get_template_definition('dropdownbuttoncontrol-fixedshare'));
define ('GD_TEMPLATE_DROPDOWNBUTTONCONTROL_RESULTSSHARE', PoP_TemplateIDUtils::get_template_definition('dropdownbuttoncontrol-resultsshare'));
define ('GD_TEMPLATE_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST', PoP_TemplateIDUtils::get_template_definition('dropdownbuttoncontrol-addrelatedpost'));
define ('GD_TEMPLATE_DROPDOWNBUTTONCONTROL_PAGEWITHSIDEOPTIONS', PoP_TemplateIDUtils::get_template_definition('dropdownbuttoncontrol-pagewithsideoptions'));
define ('GD_TEMPLATE_DROPDOWNBUTTONCONTROL_SINGLEOPTIONS', PoP_TemplateIDUtils::get_template_definition('dropdownbuttoncontrol-singleoptions'));
define ('GD_TEMPLATE_DROPDOWNBUTTONCONTROL_QUICKVIEWSINGLEOPTIONS', PoP_TemplateIDUtils::get_template_definition('dropdownbuttoncontrol-quickviewsingleoptions'));
define ('GD_TEMPLATE_DROPDOWNBUTTONCONTROL_AUTHOROPTIONS', PoP_TemplateIDUtils::get_template_definition('dropdownbuttoncontrol-authoroptions'));
define ('GD_TEMPLATE_DROPDOWNBUTTONCONTROL_QUICKVIEWAUTHOROPTIONS', PoP_TemplateIDUtils::get_template_definition('dropdownbuttoncontrol-quickviewauthoroptions'));
define ('GD_TEMPLATE_DROPDOWNBUTTONCONTROL_HOMEOPTIONS', PoP_TemplateIDUtils::get_template_definition('dropdownbuttoncontrol-homeoptions'));
define ('GD_TEMPLATE_DROPDOWNBUTTONCONTROL_QUICKVIEWHOMEOPTIONS', PoP_TemplateIDUtils::get_template_definition('dropdownbuttoncontrol-quickviewhomeoptions'));
define ('GD_TEMPLATE_DROPDOWNBUTTONCONTROL_TAGOPTIONS', PoP_TemplateIDUtils::get_template_definition('dropdownbuttoncontrol-tagoptions'));
define ('GD_TEMPLATE_DROPDOWNBUTTONCONTROL_QUICKVIEWTAGOPTIONS', PoP_TemplateIDUtils::get_template_definition('dropdownbuttoncontrol-quickviewtagoptions'));

class GD_Template_Processor_DropdownButtonControls extends GD_Template_Processor_DropdownButtonControlsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_DROPDOWNBUTTONCONTROL_SHARE,
			GD_TEMPLATE_DROPDOWNBUTTONCONTROL_FIXEDSHARE,
			GD_TEMPLATE_DROPDOWNBUTTONCONTROL_RESULTSSHARE,
			GD_TEMPLATE_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST,
			GD_TEMPLATE_DROPDOWNBUTTONCONTROL_PAGEWITHSIDEOPTIONS,
			GD_TEMPLATE_DROPDOWNBUTTONCONTROL_SINGLEOPTIONS,
			GD_TEMPLATE_DROPDOWNBUTTONCONTROL_QUICKVIEWSINGLEOPTIONS,
			GD_TEMPLATE_DROPDOWNBUTTONCONTROL_AUTHOROPTIONS,
			GD_TEMPLATE_DROPDOWNBUTTONCONTROL_QUICKVIEWAUTHOROPTIONS,
			GD_TEMPLATE_DROPDOWNBUTTONCONTROL_HOMEOPTIONS,
			GD_TEMPLATE_DROPDOWNBUTTONCONTROL_QUICKVIEWHOMEOPTIONS,
			GD_TEMPLATE_DROPDOWNBUTTONCONTROL_TAGOPTIONS,
			GD_TEMPLATE_DROPDOWNBUTTONCONTROL_QUICKVIEWTAGOPTIONS,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_FIXEDSHARE:

				$ret[] = GD_TEMPLATE_ANCHORCONTROL_FIXEDSHARE_FACEBOOK;
				$ret[] = GD_TEMPLATE_ANCHORCONTROL_FIXEDSHARE_TWITTER;
				$ret[] = GD_TEMPLATE_ANCHORCONTROL_SHARE_INVITENEWUSERS;
				break;
		
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_SHARE:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_RESULTSSHARE:

				$modules = array();
				$modules[] = GD_TEMPLATE_ANCHORCONTROL_SHARE_FACEBOOK;
				$modules[] = GD_TEMPLATE_ANCHORCONTROL_SHARE_TWITTER;

				if ($template_id == GD_TEMPLATE_DROPDOWNBUTTONCONTROL_RESULTSSHARE) {

					$modules[] = GD_TEMPLATE_ANCHORCONTROL_COPYSEARCHURL;
				}

				// Add through hook in PoP Generic Forms Processors
				// if (defined('GD_TEMPLATE_ANCHORCONTROL_SHAREBYEMAIL')) {
				// 	$ret[] = GD_TEMPLATE_ANCHORCONTROL_SHAREBYEMAIL;
				// }
				$modules[] = GD_TEMPLATE_ANCHORCONTROL_EMBED;
				$modules[] = GD_TEMPLATE_ANCHORCONTROL_PRINT;
				$modules[] = GD_TEMPLATE_ANCHORCONTROL_API;

				// Allow PoP Generic Forms Processors to add modules
				$modules = apply_filters(
					'GD_Template_Processor_DropdownButtonControls:modules:share',
					$modules,
					$template_id
				);
				$ret = array_merge(
					$ret,
					$modules
				);
				break;

			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_PAGEWITHSIDEOPTIONS:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_SINGLEOPTIONS:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_AUTHOROPTIONS:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_HOMEOPTIONS:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_TAGOPTIONS:

				$ret[] = GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFO;
				$ret[] = GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFOXS;
				$ret[] = GD_TEMPLATE_ANCHORCONTROL_CLOSEPAGE;
				break;

			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_QUICKVIEWSINGLEOPTIONS:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_QUICKVIEWAUTHOROPTIONS:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_QUICKVIEWHOMEOPTIONS:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_QUICKVIEWTAGOPTIONS:

				$ret[] = GD_TEMPLATE_ANCHORCONTROL_TOGGLEQUICKVIEWINFO;
				$ret[] = GD_TEMPLATE_ANCHORCONTROL_CLOSEPAGE;
				break;

			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST:

				$ret = array_merge(
					$ret,
					apply_filters(
						'GD_Template_Processor_DropdownButtonControls:addrelatedpost-dropdown:buttons',
						array()
					)
				);
				break;
		}
		
		return $ret;
	}

	function get_btn_class($template_id) {

		switch ($template_id) {
		
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_FIXEDSHARE:

				return 'btn btn-default btn-block';

			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_SHARE:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_RESULTSSHARE:

				return 'btn btn-compact btn-link';

			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_PAGEWITHSIDEOPTIONS:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_SINGLEOPTIONS:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_QUICKVIEWSINGLEOPTIONS:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_AUTHOROPTIONS:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_QUICKVIEWAUTHOROPTIONS:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_HOMEOPTIONS:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_QUICKVIEWHOMEOPTIONS:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_TAGOPTIONS:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_QUICKVIEWTAGOPTIONS:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST:

				return 'btn btn-link';
		}
		
		return parent::get_btn_class($template_id);
	}

	function get_fontawesome($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_FIXEDSHARE:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_SHARE:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_RESULTSSHARE:

				return 'fa-share';

			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_PAGEWITHSIDEOPTIONS:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_SINGLEOPTIONS:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_QUICKVIEWSINGLEOPTIONS:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_AUTHOROPTIONS:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_QUICKVIEWAUTHOROPTIONS:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_HOMEOPTIONS:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_QUICKVIEWHOMEOPTIONS:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_TAGOPTIONS:
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_QUICKVIEWTAGOPTIONS:

				return 'fa-ellipsis-v';
		
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST:

				return 'fa-reply';
		}

		return parent::get_fontawesome($template_id, $atts);
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_FIXEDSHARE:

				return apply_filters(
					'GD_Template_Processor_DropdownButtonControls:label:fixedshare',
					__('Share with your friends', 'pop-coreprocessors')
				).' <span class="caret"></span>';

			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST:

				// return __('Add response/additional', 'pop-coreprocessors').' <span class="caret"></span>';
				return __('Reply with...', 'pop-coreprocessors').' <span class="caret"></span>';
		}

		return parent::get_label($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
					
			case GD_TEMPLATE_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST:
				
				$this->append_att($template_id, $atts, 'class', 'pop-addrelatedpost-dropdown');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_DropdownButtonControls();