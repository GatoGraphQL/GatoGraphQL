<?php

require_once 'pop-serverside-rendering-factory.php';
require_once 'pop-serverside-rendering.php';
require_once 'pop-serverside-rendering-utils.php';
require_once 'pop-serverside-libraries-factory.php';

// Load the libraries only if the Server-side rendering is enabled, otherwise no need
if (PoP_Frontend_ServerUtils::use_serverside_rendering()) {
	require_once 'libraries/load.php';
}
