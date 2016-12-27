<?php

// The ETag header is needed to compare the versions of the JSON code cached by the Service Worker, and
// if it became stale, then refresh it
add_filter('PoP_Engine:output_json:add_etag_header', '__return_true');