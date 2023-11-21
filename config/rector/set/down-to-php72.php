<?php

declare(strict_types=1);

use PoP\PoP\Extensions\Rector\EarlyReturn\Rector\If_\ChangeIfOrReturnToEarlyReturnRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\DowngradeLevelSetList;
use Rector\Set\ValueObject\DowngradeSetList;

return static function (RectorConfig $rectorConfig): void {
    // @see https://github.com/rectorphp/rector/issues/8321
    $rectorConfig->rules([
        ChangeIfOrReturnToEarlyReturnRector::class,
    ]);
    $rectorConfig->sets([DowngradeLevelSetList::DOWN_TO_PHP_73, DowngradeSetList::PHP_73]);
};
