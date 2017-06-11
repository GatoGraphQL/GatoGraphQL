<?php

// Use composer autoloader
require_once('vendor/autoload.php');

use LightnCandy\LightnCandy;

// Taken from https://secure.php.net/manual/en/features.commandline.php
// Args passed through command line
parse_str(implode('&', array_slice($argv, 1)), $_GET);
// input=.tmpl file to be converted to PHP using LightnCandy
$input = $_GET['input'];
// output=PHP filename
$output = $_GET['output'];
// require=PHP files to be required (eg: to define helper functions, and modify variable $helper_functions)
$require = $_GET['require'];

// Validate that all required arguments have been passed
$errors = array();
if (!$input) {
	$errors[] = "input parameter is missing";
}
if (!$output) {
	$errors[] = "output parameter is missing";
}
if ($errors) {
	echo "There are some errors: ".PHP_EOL.implode(PHP_EOL, $errors).PHP_EOL;
	die;
}

// The helpers must be passed from the command line, so that plugins can add their own helpers
// Variable $helper_functions can be modified
$helper_functions = array();
if ($require) {
	foreach ($require as $require_file) {

		require_once($require_file);
	}
}

// $template = "Welcome {{name}} , You win \${{value}} dollars!!\n";
$template = file_get_contents($input);

// set compiled PHP code into $phpStr
$phpStr = LightnCandy::compile($template, array(
	'flags' => LightnCandy::FLAG_PARENT | LightnCandy::FLAG_ELSE | LightnCandy::FLAG_ERROR_LOG | LightnCandy::FLAG_THIS | LightnCandy::FLAG_NAMEDARG | LightnCandy::FLAG_SPVARS | LightnCandy::FLAG_HBESCAPE | LightnCandy::FLAG_JS/* (= LightnCandy::FLAG_JSTRUE | LightnCandy::FLAG_JSOBJECT | LightnCandy::FLAG_JSLENGTH) */ | LightnCandy::FLAG_BESTPERFORMANCE /*( = LightnCandy::FLAG_ECHO | LightnCandy::FLAG_STANDALONEPHP) */,
	'helpers' => $helper_functions,
));  

// Save the compiled PHP code into a php file
file_put_contents($output, '<?php'.PHP_EOL.$phpStr.PHP_EOL.'?>');