<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\ModuleProcessors;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\CustomPosts\ModuleProcessors\FormInputs\FilterInputModuleProcessor as CustomPostFilterInputModuleProcessor;
use PoPSchema\Posts\ModuleProcessors\FilterInnerModuleProcessor as UpstreamFilterInnerModuleProcessor;

class FilterInnerModuleProcessor extends UpstreamFilterInnerModuleProcessor
{
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
    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        $targetModules = [
            self::MODULE_FILTERINNER_MYPOSTS => [self::class, self::MODULE_FILTERINNER_POSTS],
            self::MODULE_FILTERINNER_MYPOSTCOUNT => [self::class, self::MODULE_FILTERINNER_POSTCOUNT],
        ];
        $modules = array_merge(
            parent::getSubmodules($targetModules[$module[1]]),
            [
                [CustomPostFilterInputModuleProcessor::class, CustomPostFilterInputModuleProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
            ]
        );
        if (
            $modules = HooksAPIFacade::getInstance()->applyFilters(
                'PostMutations:FilterInnerModuleProcessor:inputmodules',
                $modules,
                $module
            )
        ) {
            $ret = array_merge(
                $ret,
                $modules
            );
        }
        return $ret;
    }
}
