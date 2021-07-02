<?php

$compact = \PoP\ComponentModel\Environment::compactResponseJsonKeys();
define('GD_JS_RESOURCES', $compact ? 'r' : 'resources');
define('GD_SEPARATOR_RESOURCELOADER', '|');
