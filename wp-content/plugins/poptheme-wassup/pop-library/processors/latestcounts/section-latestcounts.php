<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LATESTCOUNT_WEBPOSTLINKS', PoP_ServerUtils::get_template_definition('latestcount-webpostlinks'));
define ('GD_TEMPLATE_LATESTCOUNT_AUTHOR_WEBPOSTLINKS', PoP_ServerUtils::get_template_definition('latestcount-author-webpostlinks'));
define ('GD_TEMPLATE_LATESTCOUNT_TAG_WEBPOSTLINKS', PoP_ServerUtils::get_template_definition('latestcount-tag-webpostlinks'));
define ('GD_TEMPLATE_LATESTCOUNT_WEBPOSTS', PoP_ServerUtils::get_template_definition('latestcount-webposts'));
define ('GD_TEMPLATE_LATESTCOUNT_AUTHOR_WEBPOSTS', PoP_ServerUtils::get_template_definition('latestcount-author-webposts'));
define ('GD_TEMPLATE_LATESTCOUNT_TAG_WEBPOSTS', PoP_ServerUtils::get_template_definition('latestcount-tag-webposts'));

class PoPThemeWassup_Template_Processor_SectionLatestCounts extends GD_Template_Processor_SectionLatestCountsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LATESTCOUNT_WEBPOSTLINKS,
			GD_TEMPLATE_LATESTCOUNT_AUTHOR_WEBPOSTLINKS,
			GD_TEMPLATE_LATESTCOUNT_TAG_WEBPOSTLINKS,
			GD_TEMPLATE_LATESTCOUNT_WEBPOSTS,
			GD_TEMPLATE_LATESTCOUNT_AUTHOR_WEBPOSTS,
			GD_TEMPLATE_LATESTCOUNT_TAG_WEBPOSTS,
		);
	}

	function get_object_name($template_id, $atts) {
	
		$cats = array(
			GD_TEMPLATE_LATESTCOUNT_WEBPOSTLINKS => POPTHEME_WASSUP_CAT_WEBPOSTLINKS,
			GD_TEMPLATE_LATESTCOUNT_AUTHOR_WEBPOSTLINKS => POPTHEME_WASSUP_CAT_WEBPOSTLINKS,
			GD_TEMPLATE_LATESTCOUNT_TAG_WEBPOSTLINKS => POPTHEME_WASSUP_CAT_WEBPOSTLINKS,
			GD_TEMPLATE_LATESTCOUNT_WEBPOSTS => POPTHEME_WASSUP_CAT_WEBPOSTS,
			GD_TEMPLATE_LATESTCOUNT_AUTHOR_WEBPOSTS => POPTHEME_WASSUP_CAT_WEBPOSTS,
			GD_TEMPLATE_LATESTCOUNT_TAG_WEBPOSTS => POPTHEME_WASSUP_CAT_WEBPOSTS,
		);
		if ($cat = $cats[$template_id]) {
				
			return gd_get_categoryname($cat, 'lc');
		}
	
		return parent::get_object_names($template_id, $atts);
	}

	function get_object_names($template_id, $atts) {
	
		$cats = array(
			GD_TEMPLATE_LATESTCOUNT_WEBPOSTLINKS => POPTHEME_WASSUP_CAT_WEBPOSTLINKS,
			GD_TEMPLATE_LATESTCOUNT_AUTHOR_WEBPOSTLINKS => POPTHEME_WASSUP_CAT_WEBPOSTLINKS,
			GD_TEMPLATE_LATESTCOUNT_TAG_WEBPOSTLINKS => POPTHEME_WASSUP_CAT_WEBPOSTLINKS,
			GD_TEMPLATE_LATESTCOUNT_WEBPOSTS => POPTHEME_WASSUP_CAT_WEBPOSTS,
			GD_TEMPLATE_LATESTCOUNT_AUTHOR_WEBPOSTS => POPTHEME_WASSUP_CAT_WEBPOSTS,
			GD_TEMPLATE_LATESTCOUNT_TAG_WEBPOSTS => POPTHEME_WASSUP_CAT_WEBPOSTS,
		);
		if ($cat = $cats[$template_id]) {
				
			return gd_get_categoryname($cat, 'plural-lc');;
		}
	
		return parent::get_object_names($template_id, $atts);
	}

	function get_section_classes($template_id, $atts) {

		$ret = parent::get_section_classes($template_id, $atts);

		switch ($template_id) {
			
			case GD_TEMPLATE_LATESTCOUNT_WEBPOSTLINKS:
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_WEBPOSTLINKS:
			case GD_TEMPLATE_LATESTCOUNT_TAG_WEBPOSTLINKS:

				$ret[] = 'post-'.POPTHEME_WASSUP_CAT_WEBPOSTLINKS;
				break;

			case GD_TEMPLATE_LATESTCOUNT_WEBPOSTS:
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_WEBPOSTS:
			case GD_TEMPLATE_LATESTCOUNT_TAG_WEBPOSTS:
				
				$ret[] = 'post-'.POPTHEME_WASSUP_CAT_WEBPOSTS;
				$ret[] = 'post-'.POPTHEME_WASSUP_CAT_WEBPOSTLINKS;
				// Also add all the webpost child categories
				foreach (PoPTheme_Wassup_Utils::get_webpostsection_cats() as $cat) {
					$ret[] = 'post-'.$cat;
				}
				break;
		}
	
		return $ret;
	}

	function is_author($template_id, $atts) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_WEBPOSTLINKS:
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_WEBPOSTS:
				
				return true;
		}
	
		return parent::is_author($template_id, $atts);
	}

	function is_tag($template_id, $atts) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LATESTCOUNT_TAG_WEBPOSTLINKS:
			case GD_TEMPLATE_LATESTCOUNT_TAG_WEBPOSTS:
				
				return true;
		}
	
		return parent::is_tag($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPThemeWassup_Template_Processor_SectionLatestCounts();