<?php

require_once 'constants.php';
require_once 'etag-hooks.php';
require_once 'formats.php';

require_once 'dataload/load.php';
require_once 'filter/load.php';
require_once 'formcomponent-inputs/load.php';

// IMPORTANT: Load the templates first, only then the resourceloaders, so that the templates definitions can be reused
require_once 'templates/load.php';
require_once 'resourceloaders/load.php';

require_once 'processors/load.php';

// Settings: Execute always at the end
require_once 'settings/load.php';
