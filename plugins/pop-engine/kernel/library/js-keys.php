<?php

// Make sure each JS Key is unique
define('GD_JS_MODULE', \PoP\Engine\Server\Utils::compactResponseJsonKeys() ? 'm' : 'module');
define('GD_JS_MODULES', \PoP\Engine\Server\Utils::compactResponseJsonKeys() ? 'ms' : 'modules');
define('GD_JS_SETTINGSID', \PoP\Engine\Server\Utils::compactResponseJsonKeys() ? 's' : 'settings-id');
