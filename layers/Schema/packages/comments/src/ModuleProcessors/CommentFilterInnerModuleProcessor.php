<?php

declare(strict_types=1);

namespace PoPSchema\Comments\ModuleProcessors;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterMultipleInputModuleProcessor;

class CommentFilterInnerModuleProcessor extends AbstractModuleProcessor
{
    public const MODULE_FILTERINNER_COMMENTS = 'filterinner-comments';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINNER_COMMENTS],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        $inputmodules = [
            self::MODULE_FILTERINNER_COMMENTS => [
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SEARCH],
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ORDER],
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT],
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_OFFSET],
                [CommonFilterMultipleInputModuleProcessor::class, CommonFilterMultipleInputModuleProcessor::MODULE_FILTERINPUT_DATES],
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_IDS],
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ID],
            ],
        ];
        if (
            $modules = HooksAPIFacade::getInstance()->applyFilters(
                'Comments:FilterInnerModuleProcessor:inputmodules',
                $inputmodules[$module[1]],
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
