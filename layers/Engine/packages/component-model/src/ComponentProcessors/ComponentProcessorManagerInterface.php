<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;

interface ComponentProcessorManagerInterface
{
    public function getProcessor(Component $component): ComponentProcessorInterface;
}
