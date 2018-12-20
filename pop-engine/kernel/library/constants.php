<?php

define ('POP_MSG_STARTUPERROR', __('PoP cannot load, please check on the dashboard for error messages, or notify the admin of this site.', 'pop-engine'));

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
define('POP_CONSTANT_MODULESTARTPATH_SEPARATOR', '.');

define ('GD_JSMETHOD_GROUP_MAIN', 'main');

define ('POP_PROPS_DESCENDANTATTRIBUTES', 'descendantattributes');
define ('POP_PROPS_ATTRIBUTES', 'attributes');
define ('POP_PROPS_MODULES', 'modules');

define ('POP_CONSTANT_DATAPROPERTIES', 'data-properties');
define ('POP_CONSTANT_DBOBJECTIDS', 'dbobjectids');
define ('POP_CONSTANT_FEEDBACK', 'feedback');
define ('POP_CONSTANT_META', 'meta');

define ('POP_VALUES_DEFAULT', 'default');
