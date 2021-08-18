<?php

declare(strict_types=1);

namespace PoPSchema\Menus\ModuleProcessors;

use PoPSchema\SchemaCommons\ModuleProcessors\AbstractFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;

class MenuFilterInputContainerModuleProcessor extends AbstractFilterInputContainerModuleProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public const MODULE_FILTERINPUTCONTAINER_MENUS = 'filterinputcontainer-menus';
    public const MODULE_FILTERINPUTCONTAINER_MENUCOUNT = 'filterinputcontainer-menucount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MENUS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MENUCOUNT],
        );
    }

    public function getFilterInputModules(array $module): array
    {
        $menuFilterInputModules = [
            ...$this->getIDFilterInputModules(),
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SEARCH],
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SLUGS],
        ];
        return match ($module[1]) {
            self::MODULE_FILTERINPUTCONTAINER_MENUS => [
                ...$menuFilterInputModules,
                ...$this->getPaginationFilterInputModules(),
            ],
            self::MODULE_FILTERINPUTCONTAINER_MENUCOUNT => $menuFilterInputModules,
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
