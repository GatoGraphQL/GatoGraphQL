<?php

require_once 'processor-hooks.php';
require_once 'fieldprocessor-users-functions.php';

// IMPORTANT: Load the templates first, only then the resourceloaders, so that the templates definitions can be reused
require_once 'templates/load.php';
require_once 'resourceloaders/load.php';

require_once 'processors/load.php';
require_once 'settingsprocessors/load.php';
require_once 'dataload/load.php';