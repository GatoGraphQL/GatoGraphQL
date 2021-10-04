<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\Engine\FormInputs\MultipleInputFormInput;

trait FormMultipleInputModuleProcessorTrait
{
    /**
     * @return string[]
     */
    public function getInputSubnames(array $module): array
    {
        return [];
    }

    public function getInputOptions(array $module): array
    {
        $options = parent::getInputOptions($module);
        $options['subnames'] = $this->getInputSubnames($module);
        return $options;
    }

    public function getInputClass(array $module): string
    {
        return MultipleInputFormInput::class;
    }
}
