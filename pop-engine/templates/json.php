<?php

\PoP\Engine\TemplateUtils::validate_pop_loaded(true);
\PoP\Engine\TemplateUtils::maybe_redirect();
\PoP\Engine\TemplateUtils::generate_data();

// Indicate that this is a json response in the HTTP Header
header('Content-type: application/json');

$engine = \PoP\Engine\Engine_Factory::get_instance();
// $engine->check_redirect();
// $engine->generate_data();
// // $engine->output_data();

$formatter = \PoP\Engine\Utils::get_datastructure_formatter();
echo json_encode($engine->get_output_data(), $formatter->get_json_encode_type());

// // Allow extra functionalities. Eg: Save the logged-in user meta information
// $engine->trigger_outputdata_hooks();