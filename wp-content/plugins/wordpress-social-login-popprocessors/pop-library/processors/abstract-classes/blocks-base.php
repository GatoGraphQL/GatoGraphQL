<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_WSL_Template_Processor_NetworkLinkBlocksBase extends GD_Template_Processor_BlocksBase {

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		$this->add_jsmethod($ret, 'addDomainClass');
		
		return $ret;
	}
	function get_js_setting($template_id, $atts) {

		$ret = parent::get_js_setting($template_id, $atts);

		// For function addDomainClass
		$ret['prefix'] = 'visible-notloggedin-';

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		// Visible only if the user not logged in
		$this->append_att($template_id, $atts, 'class', 'visible-notloggedin');

		// Add the LoginBlock/LoginBlockGroup target, as to add the Disabled Layer on top
		// while waiting for the server authenticating the FB/Twitter user
		// Allow to override this value. By default, it is this same block, but can be 
		// the containing BLOCKGROUP_LOGIN
		$blocktarget = '#'.$atts['block-id'];
		$this->add_att($template_id, $atts, 'loginblock-target', $blocktarget);
		$this->merge_att($template_id, $atts, 'params', array(
			'data-loginblock' => $this->get_att($template_id, $atts, 'loginblock-target')
		));
		return parent::init_atts($template_id, $atts);
	}
}