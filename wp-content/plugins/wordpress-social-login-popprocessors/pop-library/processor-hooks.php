<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_WSL_ProcessorHooks {

	function __construct() {

		add_filter(
			'GD_Template_Processor_UserAccountUtils:login:blocks', 
			array($this, 'loginchannels')
		);
		add_filter(
			'GD_Template_Processor_LoginBlockGroups:blockgroup_block_atts:hooks', 
			array($this, 'add_hook')
		);
	}

	function loginchannels($blocks) {

		// Add Facebook, Twitter, etc, after the Login Block
		$add = array(GD_WSL_TEMPLATE_BLOCK_NETWORKLINKS);
		array_splice($blocks, array_search(GD_TEMPLATE_BLOCK_LOGIN, $blocks)+1, 0, $add);
		return $blocks;
	}

	function add_hook($hooks) {

		$hooks[] = $this;
		return $hooks;
	}

	function init_atts_blockgroup_block($processor, $blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {

		// Method called by the GD_TEMPLATE_BLOCKGROUP_LOGIN processor to allow hooks to set $atts
		// Make GD_TEMPLATE_BLOCKGROUP_LOGIN the block to have a Disabled Layer on top
		// while waiting for the server authenticating the FB/Twitter user
		if ($blockgroup_block == GD_WSL_TEMPLATE_BLOCK_NETWORKLINKS) {
			
			$blocktarget = '#'.$blockgroup_atts['block-id'];
			$processor->add_att($blockgroup_block, $blockgroup_block_atts, 'loginblock-target', $blocktarget);

			// Description
			$description = sprintf(
				'<p class="text-center"><em>%s</em></p>',
				__('OR...', 'wsl-popprocessors')
			);
			$processor->add_att($blockgroup_block, $blockgroup_block_atts, 'description', $description);
		}
		if ($blockgroup_block == GD_TEMPLATE_BLOCK_LOGIN) {
			
			// Description
			$description = sprintf(
				'<p><em>%s</em></p>',
				sprintf(
					__('Log in with your <strong>%s</strong> account:', 'wsl-popprocessors'),
					get_bloginfo('name')
				)
			);
			$processor->add_att(GD_TEMPLATE_FORM_LOGIN, $blockgroup_block_atts, 'description', $description);
		}
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_WSL_ProcessorHooks();
