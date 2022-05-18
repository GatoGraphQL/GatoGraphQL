<?php

declare(strict_types=1);

namespace PoP\Engine\ComponentProcessors;

use PoP\Engine\FormInputs\MultipleInputFormInput;

trait FormMultipleInputComponentProcessorTrait
{
    /**
     * @return string[]
     */
    public function getInputSubnames(array $componentVariation): array
    {
        return [];
    }

    public function getInputOptions(array $componentVariation): array
    {
        $options = parent::getInputOptions($componentVariation);
        $options['subnames'] = $this->getInputSubnames($componentVariation);
        return $options;
    }

    public function getInputClass(array $componentVariation): string
    {
        return MultipleInputFormInput::class;
    }
}
