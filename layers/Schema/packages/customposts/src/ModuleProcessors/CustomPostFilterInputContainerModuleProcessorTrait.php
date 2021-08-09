<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\ModuleProcessors;

trait CustomPostFilterInputContainerModuleProcessorTrait
{
    /**
     * @return string[]
     */
    public function getFilterInputHookNames(): array
    {
        return [
            ...parent::getFilterInputHookNames(),
            $this->getClassFilterInputHookName(),
        ];
    }

    abstract public function getClassFilterInputHookName(): string;
}
