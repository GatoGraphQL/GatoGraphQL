<?php

declare(strict_types=1);

use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Environment;

// This Constant is needed to be able to retrieve the timestamp and replace it for nothing when generating the ETag,
// so that this random value does not modify the hash of the overall html output
define('POP_CONSTANT_UNIQUE_ID', GeneralUtils::generateRandomString());
define('POP_CONSTANT_RAND', rand());
define('POP_CONSTANT_TIME', time());

// This value will be used in the response. If compact, make sure each JS Key is unique
define('POP_RESPONSE_PROP_SUBMODULES', Environment::compactResponseJsonKeys() ? 'ms' : 'submodules');
