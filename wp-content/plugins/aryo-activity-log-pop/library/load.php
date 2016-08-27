<?php

// Comment Leo 27/06/2016: using dirname(__FILE__) because, if not, functions.php never gets loaded when clicking on a GDE download link (eg: https://demo.getpop.org/wp-content/plugins/google-document-embedder/load.php?d=https%3A%2F%2Fuploads-demo.getpop.org%2Fwp-content%2Fuploads%2Fgetpop-demo%2F2016%2F06%2FScience-2009.pdf)
require_once dirname(__FILE__).'/actions.php';
require_once dirname(__FILE__).'/status.php';
require_once dirname(__FILE__).'/classes.php';
require_once dirname(__FILE__).'/functions.php';
require_once dirname(__FILE__).'/navigation.php';
require_once dirname(__FILE__).'/classes/load.php';
require_once dirname(__FILE__).'/hooks/load.php';
require_once dirname(__FILE__).'/admin/load.php';