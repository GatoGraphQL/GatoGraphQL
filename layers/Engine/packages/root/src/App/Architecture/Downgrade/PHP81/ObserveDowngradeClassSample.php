<?php

declare(strict_types=1);

namespace PoP\Root\App\Architecture\Downgrade\PHP81;

/**
 * We use Reflection to find out if the code has been downgraded.
 * 
 * This sample class contains a feature from PHP 8.1: readonly properties.
 * If this feature is not present in the code anymore, then it's been downgraded.
 *
 * @see AppArchitecture::isDowngraded()
 */
class ObserveDowngradeClassSample
{
    protected readonly string $property;
}
