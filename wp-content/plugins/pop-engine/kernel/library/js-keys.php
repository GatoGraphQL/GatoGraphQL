<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Params
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Make sure each JS Key is unique
define ('GD_JS_TEMPLATE', PoP_ServerUtils::compact_js_keys() ? 't' : 'template');
define ('GD_JS_MODULES', PoP_ServerUtils::compact_js_keys() ? 'm' : 'modules');
define ('GD_JS_SUBCOMPONENTS', PoP_ServerUtils::compact_js_keys() ? 'sb' : 'subcomponents');
define ('GD_JS_SETTINGSID', PoP_ServerUtils::compact_js_keys() ? 's' : 'settings-id');
