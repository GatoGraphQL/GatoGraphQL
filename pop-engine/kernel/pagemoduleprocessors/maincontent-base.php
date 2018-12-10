<?php

abstract class PoP_Module_MainContentPageModuleProcessorBase extends PoP_Module_PageModuleProcessorBase {

	function get_groups() {

		// If no group specified, then use the "Content Module" one (initially representing the entry module, and overridable)
		// Is it overridable, so the theme can also set group "Page Section Main Content" in addition
		return apply_filters(
			'PoP_Module_PageModuleProcessorBase:maincontentgroups',
			array(
				POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE,
			)
		);
	}
}