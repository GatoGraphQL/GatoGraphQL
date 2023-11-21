<?php

declare(strict_types=1);

use PoP\PoP\Extensions\Rector\EarlyReturn\Rector\If_\ChangeIfOrReturnToEarlyReturnRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    // @todo Add GitHub issue explaining problem
    $rectorConfig->rules([
        ChangeIfOrReturnToEarlyReturnRector::class,
    ]);
};
