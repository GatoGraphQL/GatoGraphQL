<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Misc\GeneralUtils;

define('POP_MSG_STARTUPERROR', TranslationAPIFacade::getInstance()->__('PoP cannot load, please check on the dashboard for error messages, or notify the admin of this site.', 'pop-engine'));

// This Constant is needed to be able to retrieve the timestamp and replace it for nothing when generating the ETag,
// so that this random value does not modify the hash of the overall html output
define('POP_CONSTANT_UNIQUE_ID', GeneralUtils::generateRandomString());
define('POP_CONSTANT_CURRENTTIMESTAMP', current_time('timestamp'));
define('POP_CONSTANT_RAND', rand());
define('POP_CONSTANT_TIME', time());

const POP_CACHEPLACEHOLDER_UNIQUE_ID = '%ID%';
const POP_CACHEPLACEHOLDER_CURRENTTIMESTAMP = '%TIMESTAMP%';
const POP_CACHEPLACEHOLDER_RAND = '%RAND%';
const POP_CACHEPLACEHOLDER_TIME = '%TIME%';

const POP_CONSTANT_ID_SEPARATOR = '_';
const POP_CONSTANT_MODULESTARTPATH_SEPARATOR = '.';

const GD_JSMETHOD_GROUP_MAIN = 'main';

const POP_PROPS_DESCENDANTATTRIBUTES = 'descendantattributes';
const POP_PROPS_ATTRIBUTES = 'attributes';
const POP_PROPS_SUBMODULES = 'submodules';

const POP_CONSTANT_DATAPROPERTIES = 'data-properties';
const POP_CONSTANT_DBOBJECTIDS = 'dbobjectids';
const POP_CONSTANT_FEEDBACK = 'feedback';
const POP_CONSTANT_META = 'meta';

const POP_VALUES_DEFAULT = 'default';

const POP_CONSTANT_PARAMVALUE_SEPARATOR = ',';

