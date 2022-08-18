<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

class RootComponentProcessors extends AbstractComponentProcessor
{
    public final const COMPONENT_EMPTY = 'empty';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_EMPTY,
        );
    }
}
