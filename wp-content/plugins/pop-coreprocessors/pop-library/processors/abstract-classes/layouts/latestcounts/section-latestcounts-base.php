<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_SectionLatestCountsBase extends GD_Template_Processor_LatestCountsBase {

	function get_classes($template_id, $atts) {

		$ret = parent::get_classes($template_id, $atts);
		if ($section_classes = $this->get_section_classes($template_id, $atts)) {

			$pre = '';
			if ($this->is_author($template_id, $atts)) {

				global $author;
				$ret[] = 'author'.$author;
				$pre = 'author-';
			}
			elseif ($this->is_single($template_id, $atts)) {

				global $post;
				$ret[] = 'single'.$post->ID;
				$pre = 'single-';
			}

			foreach ($section_classes as $section_class) {
				
				$ret[] = $pre.$section_class;
			}

			if ($this->is_author($template_id, $atts)) {

				// Allow URE to add the organization members in the organization's feed
				$ret = GD_LatestCounts_Utils::author_filters($ret, $template_id, $atts);
			}
		}
	
		return $ret;
	}

	function get_section_classes($template_id, $atts) {

		return array();
	}

	function is_author($template_id, $atts) {

		return false;
	}

	function is_single($template_id, $atts) {

		return false;
	}
}