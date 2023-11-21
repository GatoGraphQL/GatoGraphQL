<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\DowngradeLevelSetList;
use Rector\Set\ValueObject\DowngradeSetList;

return static function (RectorConfig $rectorConfig): void {
    // @todo Add GitHub issue explaining problem
    $rectorConfig->rules([
        ChangeIfOrReturnToEarlyReturnRector::class,
    ]);
    $rectorConfig->sets([DowngradeLevelSetList::DOWN_TO_PHP_73, DowngradeSetList::PHP_73]);
};
