<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_UrlParamTextFormComponentsBase extends GD_Template_Processor_TextFormComponentsBase {

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		// If not loading the value, it's because we're retrieving the values for these in the front-end
		if (!$this->load_itemobject_value($template_id, $atts)) {

			// fill the input when showing the modal
			$this->add_jsmethod($ret, 'fillURLParamInput');
		}

		return $ret;
	}

	function is_hidden($template_id, $atts) {
	
		return true;
	}

	function is_value_selected($template_id, $atts) {
	
		if ($this->load_itemobject_value($template_id, $atts)) {

			// Comment Leo 13/06/2017: we can't use 'selected' to pass the value anymore,
			// since it will be saved in the pop-cache/atts, and then that value will be output everywhere,
			// eg: https://www.mesym.com/en/contact-user/?uid=939 will save the '939' value, and then calling ?uid=851 will still produce 939
			return true;
		}

		return parent::is_value_selected($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {
	
		// By default, load the value (assuming fetching it from a server link, eg: https://www.mesym.com/add-action/?pid=19604)
		// Do not do it for the replicable: in this case, the value will be set on front-end
		$this->add_att($template_id, $atts, 'load-itemobject-value', true);

		// If not loading the value, it's because we're retrieving the values for these in the front-end
		if ($this->load_itemobject_value($template_id, $atts)) {

			// Comment Leo 13/06/2017: we can't use 'selected' to pass the value anymore,
			// since it will be saved in the pop-cache/atts, and then that value will be output everywhere,
			// eg: https://www.mesym.com/en/contact-user/?uid=939 will save the '939' value, and then calling ?uid=851 will still produce 939
			// $this->add_att($template_id, $atts, 'selected', $this->get_value($template_id, $atts));
		}
		else {

			$this->merge_att($template_id, $atts, 'params', array(
				'data-urlparam' => $template_id
			));
		}
		
		return parent::init_atts($template_id, $atts);
	}
}