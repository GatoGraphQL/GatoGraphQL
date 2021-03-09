<?php

declare(strict_types=1);

/**
 * @todo Load container services, otherwise can't use services in tests (eg: in GeneralUtilsTest.php)
 */

use PoP\Root\Container\ContainerBuilderFactory;

require_once __DIR__ . '/../vendor/autoload.php';

ContainerBuilderFactory::init();
