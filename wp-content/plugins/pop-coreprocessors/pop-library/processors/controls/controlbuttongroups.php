<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTROLBUTTONGROUP_TOGGLEOPTIONALFIELDS', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-toggleoptionalfields'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-filter'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_CURRENTURL', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-currenturl'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCKGROUP', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-reloadblockgroup'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCK', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-reloadblock'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_LOADLATESTBLOCK', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-loadlatestblock'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_SUBMENU_XS', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-submenu-xs'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_RESETEDITOR', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-reseteditor'));
// define ('GD_TEMPLATE_CONTROLBUTTONGROUP_OPENALL', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-openall'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_SHARE', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-share'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_FIXEDSHARE', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-fixedshare'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_RESULTSSHARE', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-resultsshare'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_TOGGLESIDEINFO_BACK', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-togglesideinfo-back'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_ADDCOMMENT', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-addcomment'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_ADDRELATEDPOST', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-addrelatedpost'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_PAGEOPTIONS', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-pageoptions'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_PAGEWITHSIDEOPTIONS', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-pagewithsideoptions'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWPAGEOPTIONS', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-quickviewpageoptions'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWPAGEWITHSIDEOPTIONS', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-quickviewpagewithsideoptions'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_SINGLEOPTIONS', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-singleoptions'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_SINGLEOPTIONSBOTTOM', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-singleoptionsbottom'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWSINGLEOPTIONS', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-singlepageoptions'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_AUTHOROPTIONS', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-authoroptions'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWAUTHOROPTIONS', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-quickviewauthoroptions'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_HOMEOPTIONS', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-homeoptions'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWHOMEOPTIONS', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-quickviewhomeoptions'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_TAGOPTIONS', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-tagoptions'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWTAGOPTIONS', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-quickviewtagoptions'));
define ('GD_TEMPLATE_CONTROLBUTTONGROUP_ALLTAGSLINK', PoP_TemplateIDUtils::get_template_definition('controlbuttongroup-alltagslink'));

class GD_Template_Processor_ControlButtonGroups extends GD_Template_Processor_ControlButtonGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTROLBUTTONGROUP_TOGGLEOPTIONALFIELDS,
			GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER,
			GD_TEMPLATE_CONTROLBUTTONGROUP_CURRENTURL,
			GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCKGROUP,
			GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCK,
			GD_TEMPLATE_CONTROLBUTTONGROUP_LOADLATESTBLOCK,
			GD_TEMPLATE_CONTROLBUTTONGROUP_SUBMENU_XS,
			GD_TEMPLATE_CONTROLBUTTONGROUP_RESETEDITOR,
			// GD_TEMPLATE_CONTROLBUTTONGROUP_ADDCOMMENT,
			// GD_TEMPLATE_CONTROLBUTTONGROUP_OPENALL,
			// GD_TEMPLATE_CONTROLBUTTONGROUP_NAVIGATEBLOCK,
			// GD_TEMPLATE_CONTROLBUTTONGROUP_NAVIGATEBLOCKGROUP,
			GD_TEMPLATE_CONTROLBUTTONGROUP_SHARE,
			GD_TEMPLATE_CONTROLBUTTONGROUP_FIXEDSHARE,
			GD_TEMPLATE_CONTROLBUTTONGROUP_RESULTSSHARE,
			// GD_TEMPLATE_CONTROLBUTTONGROUP_BODYSECTION,
			GD_TEMPLATE_CONTROLBUTTONGROUP_TOGGLESIDEINFO_BACK,
			// GD_TEMPLATE_CONTROLBUTTONGROUP_MYMEMBERS,
			GD_TEMPLATE_CONTROLBUTTONGROUP_ADDCOMMENT,
			GD_TEMPLATE_CONTROLBUTTONGROUP_ADDRELATEDPOST,
			GD_TEMPLATE_CONTROLBUTTONGROUP_PAGEOPTIONS,
			GD_TEMPLATE_CONTROLBUTTONGROUP_PAGEWITHSIDEOPTIONS,
			GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWPAGEOPTIONS,
			GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWPAGEWITHSIDEOPTIONS,
			GD_TEMPLATE_CONTROLBUTTONGROUP_SINGLEOPTIONS,
			GD_TEMPLATE_CONTROLBUTTONGROUP_SINGLEOPTIONSBOTTOM,
			GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWSINGLEOPTIONS,
			GD_TEMPLATE_CONTROLBUTTONGROUP_AUTHOROPTIONS,
			GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWAUTHOROPTIONS,
			GD_TEMPLATE_CONTROLBUTTONGROUP_HOMEOPTIONS,
			GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWAUTHOROPTIONS,
			GD_TEMPLATE_CONTROLBUTTONGROUP_TAGOPTIONS,
			GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWTAGOPTIONS,
			GD_TEMPLATE_CONTROLBUTTONGROUP_ALLTAGSLINK,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_CONTROLBUTTONGROUP_TOGGLEOPTIONALFIELDS:

				$ret[] = GD_TEMPLATE_ANCHORCONTROL_TOGGLEOPTIONALFIELDS;
				break;
		
			case GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER:

				$ret[] = GD_TEMPLATE_ANCHORCONTROL_FILTERTOGGLE;
				break;
		
			case GD_TEMPLATE_CONTROLBUTTONGROUP_CURRENTURL:

				$ret[] = GD_TEMPLATE_ANCHORCONTROL_CURRENTURL;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCKGROUP:

				$ret[] = GD_TEMPLATE_BUTTONCONTROL_RELOADBLOCKGROUP;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCK:

				$ret[] = GD_TEMPLATE_BUTTONCONTROL_RELOADBLOCK;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_LOADLATESTBLOCK:

				$ret[] = GD_TEMPLATE_BUTTONCONTROL_LOADLATESTBLOCK;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_SUBMENU_XS:

				$ret[] = GD_TEMPLATE_ANCHORCONTROL_SUBMENUTOGGLE_XS;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_RESETEDITOR:

				$ret[] = GD_TEMPLATE_BUTTONCONTROL_RESETEDITOR;
				break;

			// case GD_TEMPLATE_CONTROLBUTTONGROUP_ADDCOMMENT:

			// 	// $ret[] = GD_TEMPLATE_BUTTONCONTROL_ADDCOMMENT;
			// 	$ret[] = GD_TEMPLATE_ANCHORCONTROL_ADDCOMMENT;
			// 	break;

			// case GD_TEMPLATE_CONTROLBUTTONGROUP_OPENALL:

			// 	$ret[] = GD_TEMPLATE_BUTTONCONTROL_OPENALL;
			// 	break;

			// case GD_TEMPLATE_CONTROLBUTTONGROUP_NAVIGATEBLOCK:

			// 	$ret[] = GD_TEMPLATE_BUTTONCONTROL_NAVIGATEBLOCK;
			// 	break;

			// case GD_TEMPLATE_CONTROLBUTTONGROUP_NAVIGATEBLOCKGROUP:

			// 	$ret[] = GD_TEMPLATE_BUTTONCONTROL_NAVIGATEBLOCKGROUP;
			// 	break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_SHARE:

				$ret[] = GD_TEMPLATE_DROPDOWNBUTTONCONTROL_SHARE;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_FIXEDSHARE:

				$ret[] = GD_TEMPLATE_DROPDOWNBUTTONCONTROL_FIXEDSHARE;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_RESULTSSHARE:

				$ret[] = GD_TEMPLATE_DROPDOWNBUTTONCONTROL_RESULTSSHARE;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_PAGEOPTIONS:
			// case GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWPAGEOPTIONS:

				$ret[] = GD_TEMPLATE_ANCHORCONTROL_CLOSEPAGEBTN;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_PAGEWITHSIDEOPTIONS:
			case GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWPAGEWITHSIDEOPTIONS:

				$ret[] = GD_TEMPLATE_DROPDOWNBUTTONCONTROL_PAGEWITHSIDEOPTIONS;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_SINGLEOPTIONS:

				$ret[] = GD_TEMPLATE_DROPDOWNBUTTONCONTROL_SINGLEOPTIONS;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWPAGEOPTIONS:
			case GD_TEMPLATE_CONTROLBUTTONGROUP_SINGLEOPTIONSBOTTOM:

				$ret[] = GD_TEMPLATE_ANCHORCONTROL_CLOSEPAGEBOTTOM;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWSINGLEOPTIONS:

				$ret[] = GD_TEMPLATE_DROPDOWNBUTTONCONTROL_QUICKVIEWSINGLEOPTIONS;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_AUTHOROPTIONS:

				$ret[] = GD_TEMPLATE_DROPDOWNBUTTONCONTROL_AUTHOROPTIONS;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWAUTHOROPTIONS:

				$ret[] = GD_TEMPLATE_DROPDOWNBUTTONCONTROL_QUICKVIEWAUTHOROPTIONS;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_HOMEOPTIONS:

				$ret[] = GD_TEMPLATE_DROPDOWNBUTTONCONTROL_HOMEOPTIONS;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWHOMEOPTIONS:

				$ret[] = GD_TEMPLATE_DROPDOWNBUTTONCONTROL_QUICKVIEWHOMEOPTIONS;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_TAGOPTIONS:

				$ret[] = GD_TEMPLATE_DROPDOWNBUTTONCONTROL_TAGOPTIONS;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWTAGOPTIONS:

				$ret[] = GD_TEMPLATE_DROPDOWNBUTTONCONTROL_QUICKVIEWTAGOPTIONS;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_TOGGLESIDEINFO_BACK:

				// $ret[] = GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFO_BACK;
				$ret[] = GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFOXS_BACK;
				break;

			// case GD_TEMPLATE_CONTROLBUTTONGROUP_MYMEMBERS:

			// 	$ret[] = GD_TEMPLATE_ANCHORCONTROL_MYMEMBERS;
			// 	break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_ADDCOMMENT:
				
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_ADDRELATEDPOST:
				
				$ret[] = GD_TEMPLATE_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST;
				break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_ALLTAGSLINK:

				$ret[] = GD_TEMPLATE_ANCHORCONTROL_TAGSLINK;
				break;
		}
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
					
			// case GD_TEMPLATE_CONTROLBUTTONGROUP_SHARE:
			// case GD_TEMPLATE_CONTROLBUTTONGROUP_RESULTSSHARE:
			// case GD_TEMPLATE_CONTROLBUTTONGROUP_BODYSECTION:
			// case GD_TEMPLATE_CONTROLBUTTONGROUP_BODYITEM:
			// case GD_TEMPLATE_CONTROLBUTTONGROUP_TOGGLESIDEINFO_BACK:
				
			// 	$this->append_att($template_id, $atts, 'class', 'pop-hidden-print');
			// 	break;

			case GD_TEMPLATE_CONTROLBUTTONGROUP_SUBMENU_XS:
				
				$this->append_att($template_id, $atts, 'class', 'hidden-sm hidden-md hidden-lg');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_ControlButtonGroups();