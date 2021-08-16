<?php

declare(strict_types=1);

namespace PoPSchema\Posts\ModuleProcessors;

use PoPSchema\CustomPosts\ModuleProcessors\AbstractCustomPostFilterInputContainerModuleProcessor;
use PoPSchema\CustomPosts\ModuleProcessors\FormInputs\FilterInputModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterMultipleInputModuleProcessor;

abstract class AbstractPostFilterInputContainerModuleProcessor extends AbstractCustomPostFilterInputContainerModuleProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public const MODULE_FILTERINPUTCONTAINER_POSTS = 'filterinputcontainer-posts';
    public const MODULE_FILTERINPUTCONTAINER_POSTCOUNT = 'filterinputcontainer-postcount';
    public const MODULE_FILTERINPUTCONTAINER_ADMINPOSTS = 'filterinputcontainer-adminposts';
    public const MODULE_FILTERINPUTCONTAINER_ADMINPOSTCOUNT = 'filterinputcontainer-adminpostcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_POSTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_POSTCOUNT],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ADMINPOSTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ADMINPOSTCOUNT],
        );
    }

    public function getFilterInputModules(array $module): array
    {
        $postFilterInputModules = [
            ...$this->getIDFilterInputModules(),
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SEARCH],
            [CommonFilterMultipleInputModuleProcessor::class, CommonFilterMultipleInputModuleProcessor::MODULE_FILTERINPUT_DATES],
        ];
        $statusFilterInputModules = [
            [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
        ];
        return match ($module[1]) {
            self::MODULE_FILTERINPUTCONTAINER_POSTS => [
                ...$postFilterInputModules,
                ...$this->getPaginationFilterInputModules(),
            ],
            self::MODULE_FILTERINPUTCONTAINER_POSTCOUNT => $postFilterInputModules,
            self::MODULE_FILTERINPUTCONTAINER_ADMINPOSTS => [
                ...$postFilterInputModules,
                ...$this->getPaginationFilterInputModules(),
                ...$statusFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_ADMINPOSTCOUNT => [
                ...$postFilterInputModules,
                ...$statusFilterInputModules,
            ],
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
