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

	function init_atts($template_id, &$atts) {
	
		// By default, load the value (assuming fetching it from a server link, eg: https://www.mesym.com/add-action/?pid=19604)
		// Do not do it for the replicable: in this case, the value will be set on front-end
		$this->add_att($template_id, $atts, 'load-itemobject-value', true);

		// If not loading the value, it's because we're retrieving the values for these in the front-end
		if ($this->load_itemobject_value($template_id, $atts)) {

			$this->add_att($template_id, $atts, 'selected', $this->get_value($template_id, $atts));
		}
		else {

			$this->merge_att($template_id, $atts, 'params', array(
				'data-urlparam' => $template_id
			));
		}
		
		return parent::init_atts($template_id, $atts);
	}
}