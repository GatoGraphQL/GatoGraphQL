<?php

// Make sure each JS Key is unique
define('GD_JS_MODULE', \PoP\ComponentModel\Server\Utils::compactResponseJsonKeys() ? 'm' : 'module');
define('GD_JS_SUBMODULES', \PoP\ComponentModel\Server\Utils::compactResponseJsonKeys() ? 'ms' : 'submodules');
define('GD_JS_MODULEOUTPUTNAME', \PoP\ComponentModel\Server\Utils::compactResponseJsonKeys() ? 's' : 'moduleoutputname');
