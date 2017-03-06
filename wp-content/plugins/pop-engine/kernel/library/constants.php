<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Constants
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * Hierarchy constants
 * ---------------------------------------------------------------------------------------------------------------*/

// This Constant is needed to be able to retrieve the timestamp and replace it for nothing when generating the ETag,
// so that this random value does not modify the hash of the overall html output
define('POP_CONSTANT_UNIQUE_ID', generateRandomString());
define('POP_CONSTANT_CURRENTTIMESTAMP', current_time('timestamp'));
define('POP_CONSTANT_RAND', rand());
define('POP_CONSTANT_TIME', time());

define('POP_CACHEPLACEHOLDER_UNIQUE_ID', '%ID%');
define('POP_CACHEPLACEHOLDER_CURRENTTIMESTAMP', '%TIMESTAMP%');
define('POP_CACHEPLACEHOLDER_RAND', '%RAND%');
define('POP_CACHEPLACEHOLDER_TIME', '%TIME%');

define('POP_CONSTANT_ID_SEPARATOR', '_');

define ('GD_TEMPLATEID_TOPLEVEL_SETTINGSID', 'toplevel');
define ('GD_JSMETHOD_GROUP_MAIN', 'main');


