<?php

require_once 'formats.php';
require_once 'constants.php';
require_once 'functions.php';
require_once 'pop-engine-utils.php';
require_once 'utils.php';
// require_once 'pagesection-hooks.php';

require_once 'dataload/load.php';
require_once 'formcomponent-inputs/load.php';
require_once 'filter/load.php';

// IMPORTANT: Load the templates first, only then the resourceloaders, so that the templates definitions can be reused
require_once 'templates/load.php';
require_once 'resourceloaders/load.php';

require_once 'processors/load.php';
// require_once 'cacheprocessors/load.php';
require_once 'settingsprocessors/load.php';
