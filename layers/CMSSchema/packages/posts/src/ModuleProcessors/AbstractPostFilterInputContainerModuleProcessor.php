<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\ModuleProcessors;

use PoPCMSSchema\CustomPosts\ModuleProcessors\AbstractCustomPostFilterInputContainerModuleProcessor;
use PoPCMSSchema\CustomPosts\ModuleProcessors\FormInputs\FilterInputModuleProcessor;
use PoPCMSSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;

abstract class AbstractPostFilterInputContainerModuleProcessor extends AbstractCustomPostFilterInputContainerModuleProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const MODULE_FILTERINPUTCONTAINER_POSTS = 'filterinputcontainer-posts';
    public final const MODULE_FILTERINPUTCONTAINER_POSTCOUNT = 'filterinputcontainer-postcount';
    public final const MODULE_FILTERINPUTCONTAINER_ADMINPOSTS = 'filterinputcontainer-adminposts';
    public final const MODULE_FILTERINPUTCONTAINER_ADMINPOSTCOUNT = 'filterinputcontainer-adminpostcount';

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
        ];
        $statusFilterInputModules = [
            [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
        ];
        $paginationFilterInputModules = $this->getPaginationFilterInputModules();
        return match ($module[1]) {
            self::MODULE_FILTERINPUTCONTAINER_POSTS => [
                ...$postFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_POSTCOUNT => $postFilterInputModules,
            self::MODULE_FILTERINPUTCONTAINER_ADMINPOSTS => [
                ...$postFilterInputModules,
                ...$paginationFilterInputModules,
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
