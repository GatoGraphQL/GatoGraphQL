<?php

declare(strict_types=1);

namespace PoPCMSSchema\Menus\ComponentProcessors;

use PoPCMSSchema\SchemaCommons\ComponentProcessors\AbstractFilterInputContainerComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;

class MenuFilterInputContainerComponentProcessor extends AbstractFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const MODULE_FILTERINPUTCONTAINER_MENUS = 'filterinputcontainer-menus';
    public final const MODULE_FILTERINPUTCONTAINER_MENUCOUNT = 'filterinputcontainer-menucount';

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
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::MODULE_FILTERINPUT_SEARCH],
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::MODULE_FILTERINPUT_SLUGS],
        ];
        $paginationFilterInputModules = $this->getPaginationFilterInputModules();
        return match ($module[1]) {
            self::MODULE_FILTERINPUTCONTAINER_MENUS => [
                ...$menuFilterInputModules,
                ...$paginationFilterInputModules,
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
