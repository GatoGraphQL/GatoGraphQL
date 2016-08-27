<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// TopLevel Tab Panels
define ('GD_TEMPLATE_BLOCKGROUP_CAROUSEL_WEBSITEFEATURES_FORMATS', PoP_ServerUtils::get_template_definition('blockgroup-carousel-websitefeatures-formats'));
define ('GD_TEMPLATE_BLOCKGROUP_CAROUSEL_WEBSITEFEATURES_MORE', PoP_ServerUtils::get_template_definition('blockgroup-carousel-websitefeatures-more'));

class GetPoP_Template_Processor_TopLevelCarouselBlockGroups extends GD_Template_Processor_CarouselBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_CAROUSEL_WEBSITEFEATURES_FORMATS,
			GD_TEMPLATE_BLOCKGROUP_CAROUSEL_WEBSITEFEATURES_MORE,
		);
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_CAROUSEL_WEBSITEFEATURES_FORMATS:

				$ret[] = GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_LIST;
				$ret[] = GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_FULLVIEW;

				// Allow EM to add GD_TEMPLATE_BLOCK_WHOWEARE_SCROLLMAP
				$ret = apply_filters('GD_Template_Processor_TopLevelCarouselBlockGroups:blockgroup_blocks', $ret, $template_id);
				break;

			case GD_TEMPLATE_BLOCKGROUP_CAROUSEL_WEBSITEFEATURES_MORE:

				$ret[] = GD_TEMPLATE_BLOCK_WEBSITEFEATURES_ADDITIONALS;
				$ret[] = GD_TEMPLATE_BLOCK_WEBSITEFEATURES_UNDERTHEHOOD;
				$ret[] = GD_TEMPLATE_BLOCK_WEBSITEFEATURES_IDEALFORIMPLEMENTING;
				// $ret[] = GD_TEMPLATE_BLOCK_WEBSITEFEATURES_TODOS;
				break;
		}

		return $ret;
	}

	function get_panel_header_title($blockgroup, $blockunit, $atts) {

		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_CAROUSEL_WEBSITEFEATURES_FORMATS:

				switch ($blockunit) {

					case GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_LIST:

						return sprintf(
							__('%sList', 'getpop-processors'),
							'<i class="fa fa-fw fa-th-list"></i>'
						);

					case GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_FULLVIEW:

						return sprintf(
							__('%sFull view', 'getpop-processors'),
							'<i class="fa fa-fw fa-road"></i>'
						);

					default:

						$ret = parent::get_panel_header_title($blockgroup, $blockunit, $atts);

						// Allow EM to add GD_TEMPLATE_BLOCK_WHOWEARE_SCROLLMAP
						return apply_filters('GD_Template_Processor_TopLevelCarouselBlockGroups:panel_header_title', $ret, $blockunit);
				}
				break;

			case GD_TEMPLATE_BLOCKGROUP_CAROUSEL_WEBSITEFEATURES_MORE:

				switch ($blockunit) {

					case GD_TEMPLATE_BLOCK_WEBSITEFEATURES_ADDITIONALS:

						return __('More features', 'getpop-processors');

					case GD_TEMPLATE_BLOCK_WEBSITEFEATURES_UNDERTHEHOOD:

						return __('Under the hood', 'getpop-processors');

					case GD_TEMPLATE_BLOCK_WEBSITEFEATURES_IDEALFORIMPLEMENTING:
				
						return __('Ideal for implementing...', 'getpop-processors');

					// case GD_TEMPLATE_BLOCK_WEBSITEFEATURES_TODOS:
				
					// 	return __('TODOs', 'getpop-processors');
				}
				break;
		}

		return parent::get_panel_header_title($blockgroup, $blockunit, $atts);
	}

	function get_carousel_class($template_id) {

		$ret = parent::get_carousel_class($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_CAROUSEL_WEBSITEFEATURES_FORMATS:
			// case GD_TEMPLATE_BLOCKGROUP_CAROUSEL_WEBSITEFEATURES_MORE:

				$ret .= ' stretchable';
				break;
		}

		return $ret;
	}

	function is_active_blockunit($blockgroup, $blockunit) {

		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_CAROUSEL_WEBSITEFEATURES_FORMATS:

				switch ($blockunit) {

					case GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_LIST;
						
						return true;
				}
				break;

			case GD_TEMPLATE_BLOCKGROUP_CAROUSEL_WEBSITEFEATURES_MORE:

				switch ($blockunit) {

					case GD_TEMPLATE_BLOCK_WEBSITEFEATURES_ADDITIONALS;
						
						return true;
				}
				break;
		}
	
		return parent::is_active_blockunit($blockgroup, $blockunit);
	}

	function get_title($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_CAROUSEL_WEBSITEFEATURES_FORMATS:

				return __('Different formats to display results', 'getpop-processors');

			case GD_TEMPLATE_BLOCKGROUP_CAROUSEL_WEBSITEFEATURES_MORE:

				return __('We are not finished yet...', 'getpop-processors');
		}

		return parent::get_title($template_id);
	}
	protected function get_description($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_CAROUSEL_WEBSITEFEATURES_FORMATS:

				return sprintf(
					'<pre class="breakable">%s</pre>',
					__('Posts and users can be visualized in different formats. Eg: as a list of items, full view into each, in a Google map, or you can create your own visualizations.', 'getpop-processors')
				);
		}

		return parent::get_description($template_id, $atts);
	}
	protected function get_title_htmltag($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_CAROUSEL_WEBSITEFEATURES_FORMATS:
			case GD_TEMPLATE_BLOCKGROUP_CAROUSEL_WEBSITEFEATURES_MORE:

				return 'h2';
		}

		return parent::get_title_htmltag($template_id, $atts);
	}

	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {

		switch ($blockgroup) {
		
			case GD_TEMPLATE_BLOCKGROUP_CAROUSEL_WEBSITEFEATURES_MORE:

				$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', '');
				break;
		}

		return parent::init_atts_blockgroup_block($blockgroup, $blockgroup_block, $blockgroup_block_atts, $blockgroup_atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoP_Template_Processor_TopLevelCarouselBlockGroups();
