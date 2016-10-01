<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_VotingProcessors_SubmenuHooks {

	function __construct() {

		// Before Events Manager sets its own links
		add_filter(
			'GD_Template_Processor_CustomSubMenus:author:blockgroupitems', 
			array($this, 'authorsubmenu_blockunits'),
			0,
			2
		);
		add_filter(
			'GD_Template_Processor_CustomSubMenus:tag:blockgroupitems', 
			array($this, 'tagsubmenu_blockunits'),
			0,
			2
		);
		add_filter(
			'GD_Template_Processor_CustomSubMenus:single:blockgroupitems', 
			array($this, 'singlesubmenu_blockunits'),
			0,
			2
		);
	}

	function authorsubmenu_blockunits($blockunits, $current_blockgroup) {

		if (POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES) {
			
			$opinionatedvote_subheaders = array(
				GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHOROPINIONATEDVOTES_PRO,
				GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHOROPINIONATEDVOTES_NEUTRAL,
				GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHOROPINIONATEDVOTES_AGAINST,
			);
			$blockunits[GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHOROPINIONATEDVOTES] = $opinionatedvote_subheaders;
			if (in_array($current_blockgroup, $opinionatedvote_subheaders)) {

				$blockunits[$current_blockgroup] = array();
			}
		}
		
		return $blockunits;
	}

	function tagsubmenu_blockunits($blockunits, $current_blockgroup) {

		if (POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES) {
			
			$opinionatedvote_subheaders = array(
				GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_PRO,
				GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_NEUTRAL,
				GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES_AGAINST,
			);
			$blockunits[GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGOPINIONATEDVOTES] = $opinionatedvote_subheaders;
			if (in_array($current_blockgroup, $opinionatedvote_subheaders)) {

				$blockunits[$current_blockgroup] = array();
			}
		}
		
		return $blockunits;
	}

	function singlesubmenu_blockunits($blockunits, $current_blockgroup) {

		// Place before the "Responses/Additionals" tab, so we need to recreate the original $blockunits
		// inserting a new element somewhere in the middle (can't do with array_splice with $key => $value)
		// Not adding after "Description" because it fails when selecting the "Extracts" tab, since this tab 
		// won't be next to Description tab anymore.
		$newblockunits = array();

		foreach ($blockunits as $blockgroup => $subheaders) {

			if ($blockgroup == GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDCONTENT) {

				$opinionatedvote_subheaders = array(
					GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDOPINIONATEDVOTECONTENT_PRO,
					GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDOPINIONATEDVOTECONTENT_NEUTRAL,
					GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDOPINIONATEDVOTECONTENT_AGAINST,
				);
				$newblockunits[GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDOPINIONATEDVOTECONTENT] = $opinionatedvote_subheaders;

				if (in_array($current_blockgroup, $opinionatedvote_subheaders)) {

					$newblockunits[$current_blockgroup] = array();
				}
			}
			$newblockunits[$blockgroup] = $subheaders;
		}
		
		return $newblockunits;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_VotingProcessors_SubmenuHooks();
