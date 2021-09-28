<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FilterInputProcessors;

use PoP\ComponentModel\Instances\InstanceManagerInterface;

abstract class AbstractFilterInputProcessor implements FilterInputProcessorInterface
{
    protected InstanceManagerInterface $instanceManager;
    public function __construct(InstanceManagerInterface $instanceManager)
    {
        $this->instanceManager = $instanceManager;
    }

    public function getFilterInputsToProcess(): array
    {
        return [];
    }
}
