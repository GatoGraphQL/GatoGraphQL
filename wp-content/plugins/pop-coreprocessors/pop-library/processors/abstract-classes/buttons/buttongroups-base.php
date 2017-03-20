<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ButtonGroupsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_BUTTONGROUP;
	}

	function get_header_type($template_id, $atts) {

		return 'btn-group';
	}

	function get_headers_data($template_id, $atts) {

		// The following items must be provided in the array
		return array(
			'titles' => array(),
			'icons' => array(),
			'formats' => array(),
			'screen' => null,
			'url' => null,
		);
	}

	function get_item_class($template_id, $atts) {

		return 'btn btn-xs btn-default';
	}
	function get_itemdropdown_class($template_id, $atts) {

		return 'btn-default btn-dropdown';
	}

	function get_dropdown_title($template_id, $atts) {

		return '';
	}

	function get_template_runtimeconfiguration($template_id, $atts) {
	
		$ret = parent::get_template_runtimeconfiguration($template_id, $atts);

		$vars = GD_TemplateManager_Utils::get_vars();

		// Using runtimeconfiguration, because the URL can vary for Single, it must not be cached in the configuration
		if ($header_type = $this->get_header_type($template_id, $atts)) {
			
			if ($headers_data = $this->get_headers_data($template_id, $atts)) {

				$headers = array();
				$url = $headers_data['url'];
				$default_active_format = PoPTheme_Wassup_Utils::get_defaultformat_by_screen($headers_data['screen']);
				foreach ($headers_data['formats'] as $format => $subformats) {
					
					$header = array(
						'url' => add_query_arg(GD_URLPARAM_FORMAT, $format, $url),
						'title' => $headers_data['titles'][$format],
						'fontawesome' => $headers_data['icons'][$format],
					);
					if (($vars['format'] == $format) || (!$vars['format'] && ($format == $default_active_format))) {
						
						$header['active'] = true;
					}
					if ($subformats) {

						$subheaders = array();
						foreach ($subformats as $subformat) {

							$subheader = array(
								'url' => add_query_arg(GD_URLPARAM_FORMAT, $subformat, $url),
								'title' => $headers_data['titles'][$subformat],
								'fontawesome' => $headers_data['icons'][$subformat],
							);
							if (($vars['format'] == $subformat) || (!$vars['format'] && ($subformat == $default_active_format))) {
								
								$subheader['active'] = true;
								$header['active'] = true;
							}
							$subheaders[] = $subheader;
						}

						$header['subheaders'] = $subheaders;
					}
					$headers[] = $header;
				}

				$ret['headers'] = $headers;
			}
		}
		
		return $ret;
	}

	function get_template_runtimecrawlableitem($template_id, $atts) {
	
		$ret = parent::get_template_runtimecrawlableitem($template_id, $atts);

		// Add the header links
		$runtimeconfiguration = $this->get_template_runtimeconfiguration($template_id, $atts);
		if ($headers = $runtimeconfiguration['headers']) {

			foreach ($headers as $header) {

				$ret[] = sprintf(
					'<a href="%s">%s</a>',
					$header['url'],
					$header['title']
				);

				if ($subheaders = $header['subheaders']) {
					foreach ($subheaders as $subheader) {
						$ret[] = sprintf(
							'<a href="%s">%s</a>',
							$subheader['url'],
							$subheader['title']
						);
					}
				}
			}
		}
		
		return $ret;
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		// Fill in all the properties
		if ($header_type = $this->get_header_type($template_id, $atts)) {
			
			$ret['type'] = $header_type;

			if ($item_class = $this->get_item_class($template_id, $atts)) {
				
				$ret[GD_JS_CLASSES/*'classes'*/]['item'] = $item_class;
			}
			if ($itemdropdown_class = $this->get_itemdropdown_class($template_id, $atts)) {
				
				$ret[GD_JS_CLASSES/*'classes'*/]['item-dropdown'] = $itemdropdown_class;
			}

			if ($header_type == 'dropdown') {

				if ($dropdown_title = $this->get_dropdown_title($template_id, $atts)) {
			
					$ret[GD_JS_TITLES/*'titles'*/] = array(
						'dropdown' => $dropdown_title
					);
				}
			}
		}
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		if ($header_type = $this->get_header_type($template_id, $atts)) {
			
			// header type 'btn-group' needs that same class
			$this->append_att($template_id, $atts, 'class', $header_type);
		}
		
		return parent::init_atts($template_id, $atts);
	}
}