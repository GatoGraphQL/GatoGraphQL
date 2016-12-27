<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Constants
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * Hierarchy constants
 * ---------------------------------------------------------------------------------------------------------------*/

define('POP_CONSTANT_UNIQUE_ID_CACHEPLACEHOLDER', '%ID%');
define('POP_CONSTANT_UNIQUE_ID', generateRandomString());
define('POP_CONSTANT_ID_SEPARATOR', '_');

// This Constant is needed to be able to retrieve the timestamp and replace it for nothing when generating the ETag,
// so that this random value does not modify the hash of the overall html output
define('POP_CONSTANT_CURRENTTIMESTAMP',  current_time('timestamp'));

define ('GD_TEMPLATEID_TOPLEVEL_SETTINGSID', 'toplevel');
define ('GD_JSMETHOD_GROUP_MAIN', 'main');


