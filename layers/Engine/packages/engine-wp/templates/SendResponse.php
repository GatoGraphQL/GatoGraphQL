<?php

declare(strict_types=1);

use PoP\Root\App;

/**
 * Send the Response to the client
 */
App::getResponse()->sendHeaders();
echo App::getResponse()->getContent();
