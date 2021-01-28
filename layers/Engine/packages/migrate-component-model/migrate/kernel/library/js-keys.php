<?php

// Make sure each JS Key is unique
define('GD_JS_SUBMODULES', \PoP\ComponentModel\Environment::compactResponseJsonKeys() ? 'ms' : 'submodules');
