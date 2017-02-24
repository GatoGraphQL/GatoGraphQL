<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_TARGETURL', PoP_ServerUtils::get_template_definition('formcomponent-targeturl'));
define ('GD_TEMPLATE_FORMCOMPONENT_TARGETTITLE', PoP_ServerUtils::get_template_definition('formcomponent-targettitle'));
define ('GD_TEMPLATE_FORMCOMPONENT_SENDERNAME', PoP_ServerUtils::get_template_definition('sendername'));
define ('GD_TEMPLATE_FORMCOMPONENT_BROWSERURL', PoP_ServerUtils::get_template_definition('redirect_to', true));
define ('GD_TEMPLATE_FORMCOMPONENT_FILTERNAME', PoP_ServerUtils::get_template_definition('filtername', true));
define ('GD_TEMPLATE_FILTERFORMCOMPONENT_SEARCH', PoP_ServerUtils::get_template_definition('searchfor', true));
define ('GD_TEMPLATE_FILTERFORMCOMPONENT_HASHTAGS', PoP_ServerUtils::get_template_definition('hashtags', true));
define ('GD_TEMPLATE_FILTERFORMCOMPONENT_NAME', PoP_ServerUtils::get_template_definition('nombre', true));

class GD_Template_Processor_TextFormComponentInputs extends GD_Template_Processor_TextFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FILTERFORMCOMPONENT_SEARCH,
			GD_TEMPLATE_FILTERFORMCOMPONENT_HASHTAGS,
			GD_TEMPLATE_FILTERFORMCOMPONENT_NAME,
			// GD_TEMPLATE_FORMCOMPONENT_TARGETIDS,
			GD_TEMPLATE_FORMCOMPONENT_TARGETURL,
			GD_TEMPLATE_FORMCOMPONENT_TARGETTITLE,
			GD_TEMPLATE_FORMCOMPONENT_BROWSERURL,
			GD_TEMPLATE_FORMCOMPONENT_FILTERNAME,
			GD_TEMPLATE_FORMCOMPONENT_SENDERNAME,
			// GD_TEMPLATE_FORMCOMPONENT_TEXT_ADDCOMMENT,
		);
	}

	function is_filtercomponent($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENT_SEARCH:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_HASHTAGS:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_NAME:

				return true;
		}
		
		return parent::is_filtercomponent($template_id);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENT_SEARCH:
				
				return __('Search', 'pop-coreprocessors');

			case GD_TEMPLATE_FILTERFORMCOMPONENT_HASHTAGS:

				return __('Hashtags', 'pop-coreprocessors');

			case GD_TEMPLATE_FILTERFORMCOMPONENT_NAME;
				
				return __('Name', 'pop-coreprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_SENDERNAME:

				return __('Your name', 'pop-coreprocessors');

			// case GD_TEMPLATE_FORMCOMPONENT_TEXT_ADDCOMMENT:

			// 	return __('Write a comment...', 'pop-coreprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_pagesection_jsmethod($template_id, $atts) {

		$ret = parent::get_pagesection_jsmethod($template_id, $atts);

		switch ($template_id) {

			// case GD_TEMPLATE_FORMCOMPONENT_TARGETIDS:
			case GD_TEMPLATE_FORMCOMPONENT_TARGETTITLE:

				// fill the input when showing the modal
				$this->add_jsmethod($ret, 'fillModalInput');
				break;

			case GD_TEMPLATE_FORMCOMPONENT_TARGETURL:

				// fill the input when showing the modal
				$this->add_jsmethod($ret, 'fillModalURLInput');
				break;
		}

		return $ret;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		switch ($template_id) {

			// case GD_TEMPLATE_FORMCOMPONENT_TARGETIDS:
			case GD_TEMPLATE_FORMCOMPONENT_TARGETTITLE:

				// fill the input when a new Addon PageSection is created
				if ($atts['replicable']) {
					$this->add_jsmethod($ret, 'fillAddonInput');
				}
				break;

			case GD_TEMPLATE_FORMCOMPONENT_TARGETURL:

				// fill the input when a new Addon PageSection is created
				if ($atts['replicable']) {
					$this->add_jsmethod($ret, 'fillAddonURLInput');
				}
				break;

			case GD_TEMPLATE_FORMCOMPONENT_BROWSERURL:

				$this->add_jsmethod($ret, 'browserUrl');
				break;
		}
		return $ret;
	}

	function is_hidden($template_id, $atts) {
	
		switch ($template_id) {
		
			// case GD_TEMPLATE_FORMCOMPONENT_TARGETIDS:
			case GD_TEMPLATE_FORMCOMPONENT_TARGETURL:
			case GD_TEMPLATE_FORMCOMPONENT_TARGETTITLE:
			case GD_TEMPLATE_FORMCOMPONENT_BROWSERURL:
			case GD_TEMPLATE_FORMCOMPONENT_FILTERNAME:
			
				return true;
		}
		
		return parent::is_hidden($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {

			// case GD_TEMPLATE_FORMCOMPONENT_TARGETIDS:

			// 	$this->merge_att($template_id, $atts, 'params', array(
			// 		'data-attr' => 'target-ids'
			// 	));
			// 	break;

			case GD_TEMPLATE_FORMCOMPONENT_TARGETURL:

				$this->merge_att($template_id, $atts, 'params', array(
					'data-attr' => 'target-url'
				));
				break;

			case GD_TEMPLATE_FORMCOMPONENT_TARGETTITLE:

				$this->merge_att($template_id, $atts, 'params', array(
					'data-attr' => 'target-title'
				));
				break;

			case GD_TEMPLATE_FORMCOMPONENT_BROWSERURL:

				$this->append_att($template_id, $atts, 'class', 'pop-browserurl');	
				break;

			case GD_TEMPLATE_FORMCOMPONENT_FILTERNAME:

				global $gd_template_processor_manager;

				// $filter_object = $atts['filter-object'];
				$filter = $atts['filter'];
				$filter_processor = $gd_template_processor_manager->get_processor($filter);
				$filter_object = $filter_processor->get_filter_object($filter);
				$this->add_att($template_id, $atts, 'selected', $filter_object->get_name());
				$this->add_att($template_id, $atts, 'name', GD_FILTER_FILTERING_FIELD);
				$this->append_att($template_id, $atts, 'class', GD_FILTER_NAME_INPUT);
				break;

			case GD_TEMPLATE_FORMCOMPONENT_SENDERNAME:
				
				$this->append_att($template_id, $atts, 'class', 'visible-notloggedin');

				// If we don't use the loggedinuser-data, then show the inputs always
				if (!PoP_FormUtils::use_loggedinuser_data()) {
					$this->append_att($template_id, $atts, 'class', 'visible-always');
				}
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_TextFormComponentInputs();