<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\Root\Exception\ShouldNotHappenException;

interface ComponentProcessorManagerInterface
{
    /**
     * Return the ComponentProcessor that handles the Component
     *
     * @throws ShouldNotHappenException
     */
    public function getComponentProcessor(Component $component): ComponentProcessorInterface;
}
