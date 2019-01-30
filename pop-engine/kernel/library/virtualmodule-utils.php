<?php
namespace PoP\Engine;

class VirtualModuleUtils {

	public static function extract_virtualmodule($module) {

		// If this is a Virtual Module, then extract the atts from the module name
		if ($pos = strpos($module, POP_CONSTANT_VIRTUALMODULEATTS_SEPARATOR)) {

			$virtualmoduleatts = unserialize(substr($module, $pos+1));
			$module = substr($module, 0, $pos);
		}

		return array($module, $virtualmoduleatts);
	}

	public static function create_virtualmodule($module, $virtualmoduleatts) {

		return $module.POP_CONSTANT_VIRTUALMODULEATTS_SEPARATOR.serialize($virtualmoduleatts);
	}
}