<?php
use PoPAPI\API\Response\Schemes as APISchemes;

// This is needed to set header Content-type: application/json when doing ?output=json
// IMPORTANT: We can't call doingJSON yet, since it would generate the application state
// before $wp_query is available
// Then, as a hack, simply re-construct the logic of this function
// if (doingJSON()) {
if (($_REQUEST[\PoP\ComponentModel\Constants\Params::OUTPUT] ?? null) == \PoP\ComponentModel\Constants\Outputs::JSON
    || ($_REQUEST[\PoP\ComponentModel\Constants\Params::SCHEME] ?? null) == APISchemes::API
) {
    define('JSON_REQUEST', true);
}
