<?php

PoP_Engine_TemplateUtils::validate_pop_loaded(true);
PoP_Engine_TemplateUtils::maybe_redirect();
PoP_Engine_TemplateUtils::generate_data();

// Indicate that this is a json response in the HTTP Header
header('Content-type: application/json');

$engine = PoP_Engine_Factory::get_instance();
// $engine->check_redirect();
// $engine->generate_data();
// // $engine->output();

$formatter = PoP_ModuleManager_Utils::get_datastructure_formatter();
echo json_encode($engine->get_output_data(), $formatter->get_json_encode_type());

// // Allow extra functionalities. Eg: Save the logged-in user meta information
// $engine->trigger_output_hooks();