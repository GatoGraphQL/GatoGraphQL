<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LATESTCOUNT_TAG_ALLCONTENT', PoP_ServerUtils::get_template_definition('latestcount-tag-allcontent'));
define ('GD_TEMPLATE_LATESTCOUNT_ALLCONTENT', PoP_ServerUtils::get_template_definition('latestcount-allcontent'));
define ('GD_TEMPLATE_LATESTCOUNT_AUTHOR_ALLCONTENT', PoP_ServerUtils::get_template_definition('latestcount-author-allcontent'));
define ('GD_TEMPLATE_LATESTCOUNT_SINGLE_ALLCONTENT', PoP_ServerUtils::get_template_definition('latestcount-single-allcontent'));

class GD_Template_Processor_LatestCounts extends GD_Template_Processor_LatestCountsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LATESTCOUNT_TAG_ALLCONTENT,
			GD_TEMPLATE_LATESTCOUNT_ALLCONTENT,
			GD_TEMPLATE_LATESTCOUNT_AUTHOR_ALLCONTENT,
			GD_TEMPLATE_LATESTCOUNT_SINGLE_ALLCONTENT,
		);
	}

	function get_classes($template_id, $atts) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LATESTCOUNT_TAG_ALLCONTENT:
				
				return array(
					'tag'.get_queried_object_id()
				);
			
			case GD_TEMPLATE_LATESTCOUNT_ALLCONTENT:
				
				// return 'allcontent';
				return GD_LatestCounts_Utils::get_allcontent_classes($template_id, $atts);
			
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_ALLCONTENT:
				
				global $author;
				$ret = array(
					'author'.$author
				);

				// Add prefix "author" before each class
				$classes = GD_LatestCounts_Utils::get_allcontent_classes($template_id, $atts);
				foreach ($classes as $class) {
					$ret[] = 'author-'.$class;
				}

				return GD_LatestCounts_Utils::author_filters($ret, $template_id, $atts);
			
			case GD_TEMPLATE_LATESTCOUNT_SINGLE_ALLCONTENT:
				
				global $post;
				$ret = array(
					'single'.$post->ID
				);

				// Add prefix "single" before each class
				$classes = GD_LatestCounts_Utils::get_allcontent_classes($template_id, $atts);
				foreach ($classes as $class) {
					$ret[] = 'single-'.$class;
				}

				return $ret;
		}
	
		return parent::get_classes($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_LatestCounts();