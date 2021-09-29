<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FilterInputProcessors;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

abstract class AbstractFilterInputProcessor implements FilterInputProcessorInterface
{
    protected InstanceManagerInterface $instanceManager;

    #[Required]
    public function autowireAbstractFilterInputProcessor(InstanceManagerInterface $instanceManager): void
    {
        $this->instanceManager = $instanceManager;
    }

    public function getFilterInputsToProcess(): array
    {
        return [];
    }
}
