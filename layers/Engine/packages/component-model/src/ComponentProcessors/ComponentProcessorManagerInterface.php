<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;

interface ComponentProcessorManagerInterface
{
    /**
     * Return the ComponentProcessor that handles the Component
     */
    public function getProcessor(Component $component): ComponentProcessorInterface;
}
