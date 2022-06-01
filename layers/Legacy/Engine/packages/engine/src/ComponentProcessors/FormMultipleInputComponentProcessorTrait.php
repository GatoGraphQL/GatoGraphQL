<?php

declare(strict_types=1);

namespace PoP\Engine\ComponentProcessors;

use PoP\Engine\FormInputs\MultipleInputFormInput;

trait FormMultipleInputComponentProcessorTrait
{
    /**
     * @return string[]
     */
    public function getInputSubnames(\PoP\ComponentModel\Component\Component $component): array
    {
        return [];
    }

    public function getInputOptions(\PoP\ComponentModel\Component\Component $component): array
    {
        $options = parent::getInputOptions($component);
        $options['subnames'] = $this->getInputSubnames($component);
        return $options;
    }

    public function getInputClass(\PoP\ComponentModel\Component\Component $component): string
    {
        return MultipleInputFormInput::class;
    }
}
