<?php

// This is needed to set header Content-type: application/json when PoP Engine Web Platform is not active, meaning that we are doing JSON (even though `doingJson` is false because there is no param ?output=json)
if (!defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
    define('JSON_REQUEST', true);
}
