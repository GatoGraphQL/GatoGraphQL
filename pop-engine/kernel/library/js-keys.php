<?php

// Make sure each JS Key is unique
define ('GD_JS_MODULE', \PoP\Engine\Server\Utils::compact_response_json_keys() ? 'm' : 'module');
define ('GD_JS_MODULES', \PoP\Engine\Server\Utils::compact_response_json_keys() ? 'ms' : 'modules');
define ('GD_JS_SETTINGSID', \PoP\Engine\Server\Utils::compact_response_json_keys() ? 's' : 'settings-id');
