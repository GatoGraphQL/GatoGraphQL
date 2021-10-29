<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FilterInputProcessors;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractFilterInputProcessor implements FilterInputProcessorInterface
{
    protected ?InstanceManagerInterface $instanceManager = null;

    public function setInstanceManager(InstanceManagerInterface $instanceManager): void
    {
        $this->instanceManager = $instanceManager;
    }
    protected function getInstanceManager(): InstanceManagerInterface
    {
        return $this->instanceManager ??= $this->getInstanceManager()->getInstance(InstanceManagerInterface::class);
    }

    //#[Required]
    final public function autowireAbstractFilterInputProcessor(InstanceManagerInterface $instanceManager): void
    {
        $this->instanceManager = $instanceManager;
    }

    public function getFilterInputsToProcess(): array
    {
        return [];
    }
}
