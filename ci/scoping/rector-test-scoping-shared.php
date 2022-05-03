<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\LogicalAnd\AndAssignsToSeparateLinesRector;
use Rector\Config\RectorConfig;

/**
 * This Rector configuration imports the fully qualified classnames
 * using `use`, and removing it from the body.
 * Rule `AndAssignsToSeparateLinesRector` is not needed, but we need
 * to run at least 1 rule.
 */
function doCommonContainerConfiguration(RectorConfig $rectorConfig): void
{
    $rectorConfig->rule(AndAssignsToSeparateLinesRector::class);
    
    $rectorConfig->importNames();
    $rectorConfig->disableImportShortClasses();
};
