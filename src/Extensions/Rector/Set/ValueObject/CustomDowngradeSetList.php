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
}
