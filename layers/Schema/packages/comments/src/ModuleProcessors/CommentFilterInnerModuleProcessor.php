<?php

declare(strict_types=1);

namespace PoPSchema\Comments\ModuleProcessors;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputs;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterMultipleInputs;

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
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_ORDER],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_LIMIT],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_OFFSET],
                [CommonFilterMultipleInputs::class, CommonFilterMultipleInputs::MODULE_FILTERINPUT_DATES],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_IDS],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_ID],
            ],
        ];
        if (
            $modules = HooksAPIFacade::getInstance()->applyFilters(
                'Comments:FilterInners:inputmodules',
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
