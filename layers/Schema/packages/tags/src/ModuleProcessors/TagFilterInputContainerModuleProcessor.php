<?php

declare(strict_types=1);

namespace PoPSchema\Tags\ModuleProcessors;

use PoPSchema\SchemaCommons\ModuleProcessors\AbstractFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;

class TagFilterInputContainerModuleProcessor extends AbstractFilterInputContainerModuleProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public const MODULE_FILTERINPUTCONTAINER_TAGS = 'filterinputcontainer-tags';
    public const MODULE_FILTERINPUTCONTAINER_TAGCOUNT = 'filterinputcontainer-tagcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_TAGS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_TAGCOUNT],
        );
    }

    public function getFilterInputModules(array $module): array
    {
        $tagFilterInputModules = [
            ...$this->getIDFilterInputModules(),
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SEARCH],
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SLUGS],
        ];
        return match ($module[1]) {
            self::MODULE_FILTERINPUTCONTAINER_TAGS => [
                ...$tagFilterInputModules,
                ...$this->getPaginationFilterInputModules(),
            ],
            self::MODULE_FILTERINPUTCONTAINER_TAGCOUNT => $tagFilterInputModules,
            default => [],
        };
    }

    /**
     * @return string[]
     */
    protected function getFilterInputHookNames(): array
    {
        return [
            ...parent::getFilterInputHookNames(),
            self::HOOK_FILTER_INPUTS,
        ];
    }
}
