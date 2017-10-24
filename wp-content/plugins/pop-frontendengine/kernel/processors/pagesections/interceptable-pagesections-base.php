<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define('GD_CONSTANT_REPLICATETYPE_MULTIPLE', 'multiple');
define('GD_CONSTANT_REPLICATETYPE_SINGLE', 'single');

class GD_Template_Processor_InterceptablePageSectionsBase extends GD_Template_Processor_PageSectionsBase {

	function get_pagesection_extensions($template_id) {

		$ret = parent::get_pagesection_extensions($template_id);
		$ret[] = GD_TEMPLATEEXTENSION_PAGESECTIONREPLICABLE;
		return $ret;
	}
	// function get_template_extra_sources($template_id, $atts) {

	// 	$ret = parent::get_template_extra_sources($template_id, $atts);
	// 	$ret['extensions'][] = GD_TEMPLATEEXTENSION_PAGESECTIONREPLICABLE;
	// 	return $ret;
	// }

	function get_replicate_blocksettingsids($template_id, $atts) {

		global $gd_template_processor_manager;

		$ret = array();
		$blockunits = $this->get_blockunits($template_id);
		$replicable_blockunits = array_merge(
			$blockunits[GD_TEMPLATEBLOCKSETTINGS_REPLICABLE],
			$blockunits[GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUPREPLICABLE]
		);
		if ($replicable_blockunits) {
			foreach ($replicable_blockunits as $blockunit) {
		
				$ret[$blockunit] = array(
					GD_JS_BLOCKUNITS/*'blockunits'*/ => $gd_template_processor_manager->get_processor($blockunit)->get_settings_id($blockunit),
					GD_JS_BLOCKUNITSREPLICABLE/*'blockunits-replicable'*/ => array(),
					GD_JS_BLOCKUNITSFRAME/*'blockunits-frame'*/ => array(),
				);
			}
		}

		return $ret;
	}

	function get_data_setting($template_id, $atts) {

		$ret = parent::get_data_setting($template_id, $atts);

		if ($replicate_blocksettingsids = $this->get_replicate_blocksettingsids($template_id, $atts)) {

			$ret['iohandler-atts'][GD_DATALOAD_REPLICATEBLOCKSETTINGSIDS][$template_id] = $replicate_blocksettingsids;
		}

		return $ret;
	}

	function get_template_configuration($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = parent::get_template_configuration($template_id, $atts);	

		if ($this->intercept_skip_state_update($template_id)) {
			$ret[GD_JS_INTERCEPTSKIPSTATEUPDATE/*'intercept-skipstateupdate'*/] = true;
		}
		if ($unique_urls = $this->unique_urls($template_id)) {
			$ret[GD_JS_UNIQUEURLS/*'unique-urls'*/] = $unique_urls;
		}
		if ($replicate_types = $this->get_replicate_types($template_id)) {
			$ret[GD_JS_REPLICATETYPES/*'replicate-types'*/] = $replicate_types;
		}
		$ret[GD_JS_REPLICATEBLOCKSETTINGSIDS/*'replicate-blocksettingsids'*/] = $this->get_replicate_blocksettingsids($template_id, $atts);

		return $ret;
	}

	function get_blockunit_intercept_url($template_id, $blockunit, $atts) {

		global $gd_template_processor_manager;
		
		$blockunit_processor = $gd_template_processor_manager->get_processor($blockunit);
		return $blockunit_processor->get_dataload_source($blockunit, $atts);
	}
	
	function get_intercept_urls($template_id, $atts) {

		$ret = parent::get_intercept_urls($template_id, $atts);

		// Intercept current page
		$url = GD_TemplateManager_Utils::get_current_url();
		// Intercept replicable blocks
		// if (GD_TemplateManager_Utils::loading_frame()) {

		$blockunits = $this->get_blockunits($template_id);

		$multipleopen = GD_TemplateManager_Utils::is_multipleopen();
		$main_blockunits = array_merge(
			$blockunits[GD_TEMPLATEBLOCKSETTINGS_MAIN],
			$blockunits[GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP]
		);
		
		$replicable_blockunits = array_merge(
			$blockunits[GD_TEMPLATEBLOCKSETTINGS_REPLICABLE],
			$blockunits[GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUPREPLICABLE]
		);
		if ($replicable_blockunits) {
			foreach ($replicable_blockunits as $blockunit) {
		
				$ret[$blockunit] = $this->get_blockunit_intercept_url($template_id, $blockunit, $atts[$blockunit]);
				
				// If it is multipleopen, the logic is already handled in get_current_url()
				// If we're initially loading one of the replicable urls (eg: https://www.mesym.com/add-event/) then already add the uniqueId
				// This way, we can 'click' on the corresponding pageTab, whose url will be https://www.mesym.com/add-event/#4903572871
				// and not https://www.mesym.com/add-event/. If it was this one, when clicking on that pageTab, it would also create a new Add Event page
				if (!$multipleopen && in_array($blockunit, $main_blockunits)) {
					$url = GD_TemplateManager_Utils::add_unique_id($url);
				}
			}
		}
		// }

		$ret[$template_id] = $url;

		return $ret;
	}
	
	function intercept_skip_state_update($template_id) {

		return false;
	}

	// function intercept_withparams($template_id, $atts) {

	// 	return true;
	// }

	// function get_intercept_settings($template_id, $atts) {

	// 	$ret = parent::get_intercept_settings($template_id, $atts);

	// 	// When intercepting with params, it can intercept when editing a post, eg: https://www.mesym.com/edit-project/?_wpnonce=1d2b2eac98&pid=17836
	// 	// No need when a link contains params but we want to intercept it without them. Eg: adding a new comment, https://www.mesym.com/add-comment/?pid=19604, url to intercept is https://www.mesym.com/add-comment/
	// 	if ($this->intercept_withparams($template_id, $atts)) {
	// 		$ret[] = GD_INTERCEPTOR_WITHPARAMS;
	// 	}

	// 	return $ret;
	// }

	function get_pagesection_jsmethod($template_id, $atts) {

		$ret = parent::get_pagesection_jsmethod($template_id, $atts);

		// if ($replicate_types = $this->get_replicate_types($template_id)) {
		if ($this->get_replicate_types($template_id)) {

			// Replicate the top level? This must be done by the main pageSections
			// like GD_TEMPLATE_PAGESECTION_ADDONS_HOME and not its secondary ones, like GD_TEMPLATE_PAGESECTION_ADDONTABS_HOME
			// Also make sure to load GD_TEMPLATE_PAGESECTION_ADDONS_HOME before GD_TEMPLATE_PAGESECTION_ADDONTABS_HOME 
			// so that the topLevel gets replicated before the pageSection
			if ($this->replicate_toplevel($template_id)) {
				$this->add_jsmethod($ret, 'replicateTopLevel', 'replicate-interceptor', true);
			}
			$this->add_jsmethod($ret, 'replicatePageSection', 'replicate-interceptor');

			// $replicate_toplevel = $this->replicate_toplevel($template_id);
			// if ($replicate_type == GD_CONSTANT_REPLICATETYPE_MULTIPLE) {

			// 	if ($replicate_toplevel) {
			// 		$this->add_jsmethod($ret, 'replicateMultipleTopLevel', 'replicate-interceptor', true);
			// 	}
			// 	$this->add_jsmethod($ret, 'replicateMultiplePageSection', 'replicate-interceptor');
			// }
			// elseif ($replicate_type == GD_CONSTANT_REPLICATETYPE_SINGLE) {

			// 	if ($replicate_toplevel) {
			// 		$this->add_jsmethod($ret, 'replicateSingleTopLevel', 'replicate-interceptor', true);
			// 	}
			// 	$this->add_jsmethod($ret, 'replicateSinglePageSection', 'replicate-interceptor');
			// }
		}

		$this->add_jsmethod($ret, 'destroyPage', 'destroy-interceptor');

		return $ret;
	}

	function replicate_toplevel($template_id) {

		return false;
	}

	function get_default_replicate_type($template_id) {

		// All multiple by default
		return GD_CONSTANT_REPLICATETYPE_MULTIPLE;
	}

	function get_replicate_types($template_id) {

		$ret = array();
		
		$default = $this->get_default_replicate_type($template_id);
		foreach ($this->get_blockunits($template_id) as $blockunitgroup => $blockunits) {
			foreach ($blockunits as $blockunit) {			
				$ret[$blockunit] = $default;
			}
		}

		return $ret;
		// return GD_CONSTANT_REPLICATETYPE_MULTIPLE;
	}
	function unique_urls($template_id) {

		$replicate_types = $this->get_replicate_types($template_id);

		// By default, if it is multiple replicate, then give it a unique id to each
		// Exception: modals map: they are multiple (a modal for a combination of locations),
		// however no need to make the URL unique since the params in the URL already make it unique,
		// and so can open an already-created modal again and again without replicating a new modal window each time
		// return $this->get_replicate_type($template_id) == GD_CONSTANT_REPLICATETYPE_MULTIPLE;
		$ret = array();

		// All multiple by default
		foreach ($this->get_blockunits($template_id) as $blockunitgroup => $blockunits) {
			foreach ($blockunits as $blockunit) {			
				$ret[$blockunit] = ($replicate_types[$blockunit] == GD_CONSTANT_REPLICATETYPE_MULTIPLE);
			}
		}

		return $ret;
	}
}

