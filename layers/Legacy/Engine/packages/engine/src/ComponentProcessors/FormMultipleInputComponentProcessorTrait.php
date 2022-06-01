<?php

declare(strict_types=1);

namespace PoP\Engine\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\Engine\FormInputs\MultipleInputFormInput;

trait FormMultipleInputComponentProcessorTrait
{
    /**
     * @return string[]
     */
    public function getInputSubnames(Component $component): array
    {
        return [];
    }

    public function getInputOptions(Component $component): array
    {
        $options = parent::getInputOptions($component);
        $options['subnames'] = $this->getInputSubnames($component);
        return $options;
    }

    public function getInputClass(Component $component): string
    {
        return MultipleInputFormInput::class;
    }
}
