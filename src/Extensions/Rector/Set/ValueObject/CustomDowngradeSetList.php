<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Rector\Set\ValueObject;

use Rector\Set\Contract\SetListInterface;

/**
 * @api
 */
final class CustomDowngradeSetList implements SetListInterface
{
    /**
     * @var string
     */
    public const BEFORE_DOWNGRADE = __DIR__ . '/../../../../../config/rector/set/before-downgrade.php';
    /**
     * @var string
     */
    public const DOWN_TO_PHP_72 = __DIR__ . '/../../../../../config/rector/set/down-to-php72.php';
}
