<?php

declare(strict_types=1);

use PoP\Root\App;

/**
 * Send the Response to the client
 */
echo App::getResponse()->getContent();
App::getResponse()->sendHeaders();
