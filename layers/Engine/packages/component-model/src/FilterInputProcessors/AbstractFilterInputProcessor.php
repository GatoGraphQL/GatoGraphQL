<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FilterInputProcessors;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Services\WithInstanceManagerServiceTrait;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractFilterInputProcessor implements FilterInputProcessorInterface
{
    use WithInstanceManagerServiceTrait;
    
    public function getFilterInputsToProcess(): array
    {
        return [];
    }
}
