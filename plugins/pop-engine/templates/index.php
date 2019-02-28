<?php

\PoP\Engine\TemplateUtils::validatePopLoaded(true);
\PoP\Engine\TemplateUtils::maybeRedirect();
\PoP\Engine\TemplateUtils::generateData();

// Indicate that this is a json response in the HTTP Header
header('Content-type: application/json');

$engine = \PoP\Engine\Engine_Factory::getInstance();
// $engine->checkRedirect();
// $engine->generateData();
// // $engine->output();

$formatter = \PoP\Engine\Utils::getDatastructureFormatter();
echo json_encode($engine->getOutputData(), $formatter->getJsonEncodeType());

// // Allow extra functionalities. Eg: Save the logged-in user meta information
// $engine->triggerOutputHooks();
