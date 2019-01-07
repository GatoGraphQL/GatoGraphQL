<?php
namespace PoP\Engine\Settings\Impl;

class DefaultSettingsProcessor extends \PoP\Engine\Settings\DefaultSettingsProcessorBase {

	function pages_to_process() {

		return array();
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new DefaultSettingsProcessor();
