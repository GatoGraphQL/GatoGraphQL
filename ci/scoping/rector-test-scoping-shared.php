<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\LogicalAnd\AndAssignsToSeparateLinesRector;
use Rector\Core\Configuration\Option;
use Rector\Config\RectorConfig;

/**
 * This Rector configuration imports the fully qualified classnames
 * using `use`, and removing it from the body.
 * Rule `AndAssignsToSeparateLinesRector` is not needed, but we need
 * to run at least 1 rule.
 */
function doCommonContainerConfiguration(RectorConfig $rectorConfig): void
{
    $services = $rectorConfig->services();
    $services->set(AndAssignsToSeparateLinesRector::class);
    
    $parameters = $rectorConfig->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    $parameters->set(Option::IMPORT_SHORT_CLASSES, false);
};
