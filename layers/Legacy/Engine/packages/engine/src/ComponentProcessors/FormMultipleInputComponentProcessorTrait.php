<?php

declare(strict_types=1);

namespace PoP\Engine\ComponentProcessors;

use PoP\Engine\FormInputs\MultipleInputFormInput;

trait FormMultipleInputComponentProcessorTrait
{
    /**
     * @return string[]
     */
    public function getInputSubnames(array $component): array
    {
        return [];
    }

    public function getInputOptions(array $component): array
    {
        $options = parent::getInputOptions($component);
        $options['subnames'] = $this->getInputSubnames($component);
        return $options;
    }

    public function getInputClass(array $component): string
    {
        return MultipleInputFormInput::class;
    }
}
