<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CodesBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_CODE;
	}

	function get_code($template_id, $atts) {

		return null;
	}

	function get_html_tag($template_id, $atts) {
	
		return 'div';
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);
	
		$ret['code'] = $this->get_code($template_id, $atts);
		$ret['html-tag'] = $this->get_html_tag($template_id, $atts);
		
		return $ret;
	}

	function get_template_crawlableitem($template_id, $atts) {

		$ret = parent::get_template_crawlableitem($template_id, $atts);
		
		$configuration = $this->get_template_configuration($template_id, $atts);
	
		// Only allow the specified tags, strip all the rest, eg: iframe.
		$ret[] = PoP_CrawlableDataPrinter_Utils::strip_content_tags(sprintf(
			'<%1$s>%2$s</%1$s>',
			$configuration['html-tag'],
			$configuration['code']
		));
		
		return $ret;
	}
}
