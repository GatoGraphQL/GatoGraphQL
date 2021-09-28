<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FilterInputProcessors;

use PoP\ComponentModel\Instances\InstanceManagerInterface;

abstract class AbstractFilterInputProcessor implements FilterInputProcessorInterface
{
    protected InstanceManagerInterface $instanceManager;
    
    #[\Symfony\Contracts\Service\Attribute\Required]
    public function autowireAbstractFilterInputProcessor(InstanceManagerInterface $instanceManager)
    {
        $this->instanceManager = $instanceManager;
    }

    public function getFilterInputsToProcess(): array
    {
        return [];
    }
}
