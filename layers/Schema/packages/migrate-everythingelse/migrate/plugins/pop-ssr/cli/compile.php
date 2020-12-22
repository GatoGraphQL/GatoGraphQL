<?php

// Validate that LightNCandy has been downloaded
if (!file_exists(dirname(dirname(__FILE__)).'/vendor/zordius/lightncandy')) {
    echo "Error: LightNCandy is not installed. Please download it from https://github.com/zordius/lightncandy, and try again.".PHP_EOL;
    die;
}

// Taken from https://secure.php.net/manual/en/features.commandline.php
// Args passed through command line
parse_str(implode('&', array_slice($argv, 1)), $_GET);
// input=.tmpl file to be converted to PHP using LightnCandy
$input = $_GET['input'];
// destination=Folder where PHP file will be created
$destination = $_GET['destination'];
// require=Additional PHP files to be required (eg: to define helper functions, and modify variable $helper_functions)
$extra_require = $_GET['require'];

// Validate that all required arguments have been passed
$errors = array();
if (!$input) {
    $errors[] = "input parameter is missing";
}
if (!$destination) {
    $errors[] = "destination parameter is missing";
}
if ($errors) {
    echo "There are some errors: ".PHP_EOL.implode(PHP_EOL, $errors).PHP_EOL;
    die;
}

// Convert from .tmpl to .php, keeping the same filename, maybe changing the destination folder
$filename = str_replace('.tmpl', '.php', basename($input));
$output = trim($destination, '/').'/'.$filename;

// Everything is fine, load and execute LightNCandy

// Load LightNCandy through Composer autoloader
require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';

// Load the library
require_once dirname(__FILE__).'/helpers-registration.php';

use LightnCandy\LightnCandy;

// Load required files, containing/registering helper methods
$require = array(
    dirname(__FILE__).'/handlebars-helpers/load.php',
);
// In addition, load those files passed by the CLI args
if ($extra_require) {
    $extra_require = is_array($extra_require) ? $extra_require : array($extra_require);
    $require = array_unique(
        array_merge(
            $require,
            $extra_require
        )
    );
}
foreach ($require as $require_file) {
    include_once $require_file;
}

$template = file_get_contents($input);

// set compiled PHP code into $phpStr
$phpStr = LightnCandy::compile(
    $template,
    array(
        'flags' => LightnCandy::FLAG_ADVARNAME | LightnCandy::FLAG_PARENT | LightnCandy::FLAG_ELSE | LightnCandy::FLAG_ERROR_LOG | LightnCandy::FLAG_THIS | LightnCandy::FLAG_NAMEDARG | LightnCandy::FLAG_SPVARS | LightnCandy::FLAG_HBESCAPE | LightnCandy::FLAG_JS/* (= LightnCandy::FLAG_JSTRUE | LightnCandy::FLAG_JSOBJECT | LightnCandy::FLAG_JSLENGTH) */ | LightnCandy::FLAG_BESTPERFORMANCE /*( = LightnCandy::FLAG_ECHO | LightnCandy::FLAG_STANDALONEPHP) */,
        'helpers' => PoP_SSR_CLI_HelperRegistration::getHelperMethods(),
    )
);

// $parsed = print_r(LightnCandy::$lastParsed, true);
// echo $parsed;die;

// Save the compiled PHP code into a php file
file_put_contents($output, '<?php'.PHP_EOL.$phpStr.PHP_EOL.'?>');
