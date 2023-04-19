<?php

declare(strict_types=1);

use PoP\Root\App;

/**
 * Send the Response to the client
 */
$response = App::getResponse();
$response->sendHeaders();
echo $response->getContent();
