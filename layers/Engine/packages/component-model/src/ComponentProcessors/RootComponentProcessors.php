<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

class RootComponentProcessors extends AbstractComponentProcessor
{
    public final const COMPONENT_EMPTY = 'empty';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_EMPTY],
        );
    }
}
