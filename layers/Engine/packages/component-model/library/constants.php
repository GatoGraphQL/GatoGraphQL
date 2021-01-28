<?php

declare(strict_types=1);

use PoP\ComponentModel\Misc\GeneralUtils;

// This Constant is needed to be able to retrieve the timestamp and replace it for nothing when generating the ETag,
// so that this random value does not modify the hash of the overall html output
define('POP_CONSTANT_UNIQUE_ID', GeneralUtils::generateRandomString());
define('POP_CONSTANT_RAND', rand());
define('POP_CONSTANT_TIME', time());
