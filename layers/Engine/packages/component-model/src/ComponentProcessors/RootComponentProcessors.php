<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

class RootComponentProcessors extends AbstractComponentProcessor
{
    public final const MODULE_EMPTY = 'empty';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EMPTY],
        );
    }
}
