<?php

declare(strict_types=1);

namespace PoPSchema\Tags\ModuleProcessors;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputs;

class FilterInners extends AbstractModuleProcessor
{
    public const MODULE_FILTERINNER_TAGS = 'filterinner-tags';
    public const MODULE_FILTERINNER_TAGCOUNT = 'filterinner-tagcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINNER_TAGS],
            [self::class, self::MODULE_FILTERINNER_TAGCOUNT],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        $inputmodules = [
            self::MODULE_FILTERINNER_TAGS => [
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_ORDER],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_LIMIT],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_OFFSET],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_IDS],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_ID],
            ],
            self::MODULE_FILTERINNER_TAGCOUNT => [
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_IDS],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_ID],
            ],
        ];
        if (
            $modules = HooksAPIFacade::getInstance()->applyFilters(
                'Tags:FilterInners:inputmodules',
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



