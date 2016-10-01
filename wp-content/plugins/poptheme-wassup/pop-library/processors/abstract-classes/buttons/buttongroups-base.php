<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CustomButtonGroupsBase extends GD_Template_Processor_ButtonGroupsBase {

	function get_headers_data($template_id, $atts) {

		$formats = $this->get_headersdata_formats($template_id, $atts);
		
		// All possible titles and icos for PoPTheme Wassup are already set here, however we only need to provide for the correspnding formats, filter out the rest
		$allformats = array_unique(array_merge(array_keys($formats), array_flatten(array_values($formats))));
		$alltitles = $this->get_headersdata_titles($template_id, $atts);
		$allicons = $this->get_headersdata_icons($template_id, $atts);
		$titles = $icons = array();
		foreach ($allformats as $format) {
			$titles[$format] = $alltitles[$format];
			$icons[$format] = $allicons[$format];
		}
		
		return array(
			'formats' => $formats,
			'titles' => $titles,
			'icons' => $icons,
			'screen' => $this->get_headersdata_screen($template_id, $atts),
			'url' => $this->get_headersdata_url($template_id, $atts),
		);
	}
	protected function get_headersdata_titles($template_id, $atts) {

		return array(
			GD_TEMPLATEFORMAT_SIMPLEVIEW => __('Feed', 'poptheme-wassup'),
			GD_TEMPLATEFORMAT_FULLVIEW => __('Extended', 'poptheme-wassup'),
			GD_TEMPLATEFORMAT_LIST => __('List', 'poptheme-wassup'),
			GD_TEMPLATEFORMAT_THUMBNAIL => __('Thumbnail', 'poptheme-wassup'),
			GD_TEMPLATEFORMAT_DETAILS => __('Details', 'poptheme-wassup'),
			GD_TEMPLATEFORMAT_CALENDAR => __('Calendar', 'poptheme-wassup'),
			GD_TEMPLATEFORMAT_MAP => __('Map', 'poptheme-wassup'),
			GD_TEMPLATEFORMAT_TABLE => __('Edit', 'poptheme-wassup'),
		);
	}
	protected function get_headersdata_icons($template_id, $atts) {

		return array(
			GD_TEMPLATEFORMAT_SIMPLEVIEW => 'fa-angle-right',
			GD_TEMPLATEFORMAT_FULLVIEW => 'fa-angle-double-right',
			GD_TEMPLATEFORMAT_LIST => 'fa-list',
			GD_TEMPLATEFORMAT_THUMBNAIL => 'fa-th',
			GD_TEMPLATEFORMAT_DETAILS => 'fa-th-list',
			GD_TEMPLATEFORMAT_CALENDAR => 'fa-calendar',
			GD_TEMPLATEFORMAT_MAP => 'fa-map-marker',
			GD_TEMPLATEFORMAT_TABLE => 'fa-edit',
		);
	}

	protected function get_headersdataformats_hasmap($template_id, $atts) {

		return false;
	}

	protected function get_headersdata_formats($template_id, $atts) {

		// We can initially have a common format scheme depending on the screen
		$screen = $this->get_headersdata_screen($template_id, $atts);
		switch ($screen) {
			
			case POP_SCREEN_SECTION:
			case POP_SCREEN_AUTHORSECTION:
			case POP_SCREEN_SINGLESECTION:
			case POP_SCREEN_TAGSECTION:
			case POP_SCREEN_HOMESECTION:

				$formats = array(
					GD_TEMPLATEFORMAT_SIMPLEVIEW => array(
						GD_TEMPLATEFORMAT_SIMPLEVIEW,
						GD_TEMPLATEFORMAT_FULLVIEW,
					)
				);
				// Allow to add the Map (eg: events, projects)
				if ($this->get_headersdataformats_hasmap($template_id, $atts)) {
					$formats[GD_TEMPLATEFORMAT_MAP] = array();
				}
				$formats[GD_TEMPLATEFORMAT_LIST] = array(
					GD_TEMPLATEFORMAT_LIST,
					GD_TEMPLATEFORMAT_THUMBNAIL,
					GD_TEMPLATEFORMAT_DETAILS,
				);
				return $formats;
			
			case POP_SCREEN_USERS:
			case POP_SCREEN_AUTHORUSERS:
			case POP_SCREEN_SINGLEUSERS:

				$formats = array(
					GD_TEMPLATEFORMAT_FULLVIEW => array()
				);
				if ($this->get_headersdataformats_hasmap($template_id, $atts)) {
					$formats[GD_TEMPLATEFORMAT_MAP] = array();
				}
				$formats[GD_TEMPLATEFORMAT_DETAILS] = array(
					GD_TEMPLATEFORMAT_LIST,
					GD_TEMPLATEFORMAT_THUMBNAIL,
					GD_TEMPLATEFORMAT_DETAILS,
				);
				return $formats;

			case POP_SCREEN_SECTIONCALENDAR:
			case POP_SCREEN_AUTHORSECTIONCALENDAR:
			case POP_SCREEN_TAGSECTIONCALENDAR:
			
				return array(
					GD_TEMPLATEFORMAT_CALENDAR => array(),
					GD_TEMPLATEFORMAT_MAP => array(),
				);

			case POP_SCREEN_MYCONTENT:

				return array(
					GD_TEMPLATEFORMAT_TABLE => array(),
					GD_TEMPLATEFORMAT_SIMPLEVIEW => array(
						GD_TEMPLATEFORMAT_SIMPLEVIEW,
						GD_TEMPLATEFORMAT_FULLVIEW,
					),
				);
			
			case POP_SCREEN_HIGHLIGHTS:
			case POP_SCREEN_SINGLEHIGHLIGHTS:

				return array(
					GD_TEMPLATEFORMAT_FULLVIEW => array(),
					GD_TEMPLATEFORMAT_LIST => array(
						GD_TEMPLATEFORMAT_LIST,
						GD_TEMPLATEFORMAT_THUMBNAIL,
					),
				);

			case POP_SCREEN_MYHIGHLIGHTS:

				return array(
					GD_TEMPLATEFORMAT_TABLE => array(),
					GD_TEMPLATEFORMAT_FULLVIEW => array(),
				);
		}

		return array();
	}
	protected function get_headersdata_screen($template_id, $atts) {

		return null;
	}
	protected function get_headersdata_url($template_id, $atts) {

		return GD_TemplateManager_Utils::get_current_url();
	}
}