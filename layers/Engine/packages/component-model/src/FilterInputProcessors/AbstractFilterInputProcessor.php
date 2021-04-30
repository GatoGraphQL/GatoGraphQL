<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FilterInputProcessors;

use PoP\ComponentModel\Instances\InstanceManagerInterface;

abstract class AbstractFilterInputProcessor implements FilterInputProcessorInterface
{
    function __construct(
        protected InstanceManagerInterface $instanceManager,
    ) {
    }

    public function getFilterInputsToProcess(): array
    {
        return [];
    }
}
