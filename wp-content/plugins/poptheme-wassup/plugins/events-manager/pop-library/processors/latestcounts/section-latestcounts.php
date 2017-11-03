<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LATESTCOUNT_EVENTS', PoP_TemplateIDUtils::get_template_definition('latestcount-events'));
define ('GD_TEMPLATE_LATESTCOUNT_AUTHOR_EVENTS', PoP_TemplateIDUtils::get_template_definition('latestcount-author-events'));
define ('GD_TEMPLATE_LATESTCOUNT_TAG_EVENTS', PoP_TemplateIDUtils::get_template_definition('latestcount-tag-events'));

class GD_EM_Template_Processor_SectionLatestCounts extends GD_Template_Processor_SectionLatestCountsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LATESTCOUNT_EVENTS,
			GD_TEMPLATE_LATESTCOUNT_AUTHOR_EVENTS,
			GD_TEMPLATE_LATESTCOUNT_TAG_EVENTS,
		);
	}

	function get_object_name($template_id, $atts) {
	
		switch ($template_id) {
			
			case GD_TEMPLATE_LATESTCOUNT_EVENTS:
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_EVENTS:
			case GD_TEMPLATE_LATESTCOUNT_TAG_EVENTS:
				
				return __('event', 'poptheme-wassup');
		}
	
		return parent::get_object_name($template_id, $atts);
	}

	function get_object_names($template_id, $atts) {
	
		switch ($template_id) {
			
			case GD_TEMPLATE_LATESTCOUNT_EVENTS:
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_EVENTS:
			case GD_TEMPLATE_LATESTCOUNT_TAG_EVENTS:
				
				return __('events', 'poptheme-wassup');
		}
	
		return parent::get_object_names($template_id, $atts);
	}

	function get_section_classes($template_id, $atts) {

		$ret = parent::get_section_classes($template_id, $atts);

		switch ($template_id) {
			
			case GD_TEMPLATE_LATESTCOUNT_EVENTS:
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_EVENTS:
			case GD_TEMPLATE_LATESTCOUNT_TAG_EVENTS:
				
				$ret[] = EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_ALL;
				$ret[] = EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_FUTURE;
				$ret[] = EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_CURRENT;
				$ret[] = EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_EVENTLINKS;
				break;
		}
	
		return $ret;
	}

	function is_author($template_id, $atts) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_EVENTS:
				
				return true;
		}
	
		return parent::is_author($template_id, $atts);
	}

	function is_tag($template_id, $atts) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LATESTCOUNT_TAG_EVENTS:
				
				return true;
		}
	
		return parent::is_tag($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_SectionLatestCounts();