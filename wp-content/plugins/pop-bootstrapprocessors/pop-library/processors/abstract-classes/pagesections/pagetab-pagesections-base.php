<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// define ('POP_HOOK_PAGETABS_ADDOPENTAB', 'GD_Template_Processor_PageTabPageSectionsBase:js:addopentab');

class GD_Template_Processor_PageTabPageSectionsBase extends GD_Template_Processor_BootstrapPageSectionsBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_PAGESECTION_PAGETAB;
	}

	function get_btn_class($template_id, $atts) {

		return 'btn btn-default btn-sm';
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		$ret['btn-class'] = $this->get_btn_class($template_id, $atts);

		return $ret;
	}

	protected function get_atts_hierarchy_initial($template_id) {

		$atts = parent::get_atts_hierarchy_initial($template_id);
		$this->append_att($template_id, $atts, 'class', 'pop-pagesection-page pop-viewport toplevel');
		return $atts;
	}


	protected function get_initjs_blockbranches($template_id, $atts) {

		$ret = parent::get_initjs_blockbranches($template_id, $atts);

		$id = $this->get_frontend_id($template_id, $atts);
		$ret[] = '#'.$id.'-merge > div.pop-pagesection-page > div.btn-group > a.pop-pagetab-btn > span.pop-block';

		return $ret;
	}

	function get_pagesection_jsmethod($template_id, $atts) {

		$ret = parent::get_pagesection_jsmethod($template_id, $atts);

		// Important: we assign replicateTopLevel to GD_TEMPLATE_PAGESECTION_PAGETABS_HOME and not to GD_TEMPLATE_PAGESECTION_HOME because
		// the interceptor functions are executed under pageSectionNewDOMsInitialized, which will execute them by order in which they appear in the DOM
		// So since the tabs appear more at the top, they'll execute first, and so before execute replicateTopLevel
		// Also, please keep the order below: first execute replicateTopLevel and only then replicatePageSection
		// $this->add_jsmethod($ret, 'replicateMultipleTopLevel', 'replicate-interceptor', true);
		$this->add_jsmethod($ret, 'activatePageTab', 'activate-interceptor');
		$this->add_jsmethod($ret, 'onDestroyPageSwitchTab', 'remove');

		// addOpenTab only if needed. Eg: do not do it in the Embed/Print
		// if (apply_filters(POP_HOOK_PAGETABS_ADDOPENTAB, true)) {
		$this->add_jsmethod($ret, 'addOpenTab', 'remove');
		// }
		$this->add_jsmethod($ret, 'closePageTab', 'remove');

		return $ret;
	}
}

