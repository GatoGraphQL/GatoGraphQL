<?php

$compact = \PoP\ComponentModel\Server\Utils::compactResponseJsonKeys();
define('GD_JS_RESOURCES', $compact ? 'r' : 'resources');
define('GD_SEPARATOR_RESOURCELOADER', '|');
