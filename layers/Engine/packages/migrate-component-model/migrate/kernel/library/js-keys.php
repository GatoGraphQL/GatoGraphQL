<?php

// Make sure each JS Key is unique
define('GD_JS_SUBMODULES', \PoP\ComponentModel\Environment::compactResponseJsonKeys() ? 'ms' : 'submodules');
define('GD_JS_MODULEOUTPUTNAME', \PoP\ComponentModel\Environment::compactResponseJsonKeys() ? 's' : 'moduleoutputname');
