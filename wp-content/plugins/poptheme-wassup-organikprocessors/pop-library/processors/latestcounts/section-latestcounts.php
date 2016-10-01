<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LATESTCOUNT_FARMS', PoP_ServerUtils::get_template_definition('latestcount-farms'));
define ('GD_TEMPLATE_LATESTCOUNT_AUTHOR_FARMS', PoP_ServerUtils::get_template_definition('latestcount-author-farms'));
define ('GD_TEMPLATE_LATESTCOUNT_TAG_FARMS', PoP_ServerUtils::get_template_definition('latestcount-tag-farms'));

class PoPThemeWassup_OrganikProcessors_Template_Processor_SectionLatestCounts extends GD_Template_Processor_SectionLatestCountsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LATESTCOUNT_FARMS,
			GD_TEMPLATE_LATESTCOUNT_AUTHOR_FARMS,
			GD_TEMPLATE_LATESTCOUNT_TAG_FARMS,
		);
	}

	function get_object_name($template_id, $atts) {
	
		$cats = array(
			GD_TEMPLATE_LATESTCOUNT_FARMS => POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS,
			GD_TEMPLATE_LATESTCOUNT_AUTHOR_FARMS => POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS,
			GD_TEMPLATE_LATESTCOUNT_TAG_FARMS => POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS,
		);
		if ($cat = $cats[$template_id]) {
				
			return gd_get_categoryname($cat, 'lc');;
		}
	
		return parent::get_object_names($template_id, $atts);
	}

	function get_object_names($template_id, $atts) {
	
		$cats = array(
			GD_TEMPLATE_LATESTCOUNT_FARMS => POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS,
			GD_TEMPLATE_LATESTCOUNT_AUTHOR_FARMS => POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS,
			GD_TEMPLATE_LATESTCOUNT_TAG_FARMS => POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS,
		);
		if ($cat = $cats[$template_id]) {
				
			return gd_get_categoryname($cat, 'plural-lc');
		}
	
		return parent::get_object_names($template_id, $atts);
	}

	function get_section_classes($template_id, $atts) {

		$ret = parent::get_section_classes($template_id, $atts);

		switch ($template_id) {
			
			case GD_TEMPLATE_LATESTCOUNT_FARMS:
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_FARMS:
			case GD_TEMPLATE_LATESTCOUNT_TAG_FARMS:

				$ret[] = 'post-'.POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS;
				$ret[] = 'post-'.POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMLINKS;
				break;
		}
	
		return $ret;
	}

	function is_author($template_id, $atts) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_FARMS:
				
				return true;
		}
	
		return parent::is_author($template_id, $atts);
	}

	function is_tag($template_id, $atts) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LATESTCOUNT_TAG_FARMS:
				
				return true;
		}
	
		return parent::is_tag($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPThemeWassup_OrganikProcessors_Template_Processor_SectionLatestCounts();