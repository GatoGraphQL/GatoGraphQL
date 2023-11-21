<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Rector\Set\ValueObject;

use Rector\Set\Contract\SetListInterface;

/**
 * @api
 */
final class CustomDowngradeLevelSetList implements SetListInterface
{
    /**
     * @var string
     */
    public const DOWN_TO_PHP_72 = __DIR__ . '/../../../../../config/rector/set/level/down-to-php72.php';
}
