<?php

declare(strict_types=1);

use PoP\PoP\Extensions\Rector\EarlyReturn\Rector\If_\ChangeIfOrReturnToEarlyReturnRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    // @see https://github.com/rectorphp/rector/issues/8321
    $rectorConfig->rules([
        ChangeIfOrReturnToEarlyReturnRector::class,
    ]);
};
