<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FilterInputProcessors;

use PoP\ComponentModel\Services\BasicServiceTrait;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractFilterInputProcessor implements FilterInputProcessorInterface
{
    use BasicServiceTrait;

    public function getFilterInputsToProcess(): array
    {
        return [];
    }
}
