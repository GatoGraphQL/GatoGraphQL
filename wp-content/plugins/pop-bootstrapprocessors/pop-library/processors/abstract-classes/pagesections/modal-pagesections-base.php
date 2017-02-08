<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ModalPageSectionsBase extends GD_Template_Processor_BootstrapPageSectionsBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_PAGESECTION_MODAL;
	}

	function get_header_class($template_id) {

		return '';
	}
	function get_dialog_classes($template_id) {

		return array();
	}
	function get_body_classes($template_id) {

		$ret = array();

		foreach ($this->get_blockunits($template_id) as $blockunitgroup => $blockunits) {
			foreach ($blockunits as $blockunit) {			
				$ret[$blockunit] = 'modal-body';
			}
		}

		return $ret;
	}
	function get_header_titles($template_id) {

		return array();
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		if ($header_class = $this->get_header_class($template_id)) {
			$ret[GD_JS_CLASSES/*'classes'*/]['header'] = $header_class;
		}
		if ($dialogs_class = $this->get_dialog_classes($template_id)) {
			$ret[GD_JS_CLASSES/*'classes'*/]['dialogs'] = $dialogs_class;
		}
		if ($bodies_class = $this->get_body_classes($template_id)) {
			$ret[GD_JS_CLASSES/*'classes'*/]['bodies'] = $bodies_class;
		}
		if ($header_titles = $this->get_header_titles($template_id)) {
			$ret[GD_JS_TITLES/*'titles'*/]['headers'] = $header_titles;
		}

		return $ret;
	}

	function get_pagesection_jsmethod($template_id, $atts) {

		$ret = parent::get_pagesection_jsmethod($template_id, $atts);
		$this->add_jsmethod($ret, 'copyHeader', 'modal');
		return $ret;
	}

	protected function get_atts_hierarchy_initial($template_id) {

		$atts = parent::get_atts_hierarchy_initial($template_id);
	
		$this->append_att($template_id, $atts, 'class', 'pop-pagesection-page pop-viewport toplevel');
		$this->merge_att($template_id, $atts, 'params', array(
			'data-paramsscope' => GD_SETTINGS_PARAMSSCOPE_URL
		));

		return $atts;
	}

	protected function get_initjs_blockbranches($template_id, $atts) {

		$ret = parent::get_initjs_blockbranches($template_id, $atts);

		// 2 possibilities: with the merge container (eg: main) or without it (eg: quickview)
		$id = $this->get_frontend_id($template_id, $atts);
		$ret[] = '#'.$id.' > .modal.in > .pop-modaldialog > .pop-modalcontent > .pop-modalbody > .pop-block';

		return $ret;
	}
}

