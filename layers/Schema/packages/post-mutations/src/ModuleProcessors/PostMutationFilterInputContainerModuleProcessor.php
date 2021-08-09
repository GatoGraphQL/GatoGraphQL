<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\ModuleProcessors;

use PoPSchema\CustomPosts\ModuleProcessors\FormInputs\FilterInputModuleProcessor as CustomPostFilterInputModuleProcessor;
use PoPSchema\Posts\ModuleProcessors\AbstractPostFilterInputContainerModuleProcessor;

class PostMutationFilterInputContainerModuleProcessor extends AbstractPostFilterInputContainerModuleProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public const MODULE_FILTERINNER_MYPOSTS = 'filterinner-myposts';
    public const MODULE_FILTERINNER_MYPOSTCOUNT = 'filterinner-mypostcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINNER_MYPOSTS],
            [self::class, self::MODULE_FILTERINNER_MYPOSTCOUNT],
        );
    }

    /**
     * Retrieve the same elements as for Posts, and add the "status" filter
     */
    public function getFilterInputModules(array $module): array
    {
        $targetModule = match ($module[1]) {
            self::MODULE_FILTERINNER_MYPOSTS => [self::class, self::MODULE_FILTERINNER_POSTS],
            self::MODULE_FILTERINNER_MYPOSTCOUNT => [self::class, self::MODULE_FILTERINNER_POSTCOUNT],
            default => null,
        };
        return array_merge(
            parent::getFilterInputModules($targetModule),
            [
                [CustomPostFilterInputModuleProcessor::class, CustomPostFilterInputModuleProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
            ]
        );
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
