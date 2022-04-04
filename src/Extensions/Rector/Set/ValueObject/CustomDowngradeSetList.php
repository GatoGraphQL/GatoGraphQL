<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Rector\Set\ValueObject;

use Rector\Set\Contract\SetListInterface;

/**
 * Replace the current `DowngradeParameterTypeWideningRector` (because it takes too long)
 * with a "legacy" version (from up to v0.10.9), which is fast
 * but does not replace code within traits.
 *
 * @see https://github.com/leoloso/PoP/issues/715
 */
final class CustomDowngradeSetList implements SetListInterface
{
    /**
     * @var string
     */
    public final const PHP_72 = __DIR__ . '/../../../../../config/extensions/rector/set/downgrade-php72.php';
}
