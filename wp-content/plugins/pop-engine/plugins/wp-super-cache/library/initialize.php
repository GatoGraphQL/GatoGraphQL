<?php

// This is needed to set header Content-type: application/json when doing ?output=json
if (doing_json()) {

	define('JSON_REQUEST', true);
}