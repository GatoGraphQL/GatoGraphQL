<?php

declare(strict_types=1);

namespace PoPCMSSchema\Menus\ComponentProcessors;

use PoPCMSSchema\SchemaCommons\ComponentProcessors\AbstractFilterInputContainerComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;

class MenuFilterInputContainerComponentProcessor extends AbstractFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const COMPONENT_FILTERINPUTCONTAINER_MENUS = 'filterinputcontainer-menus';
    public final const COMPONENT_FILTERINPUTCONTAINER_MENUCOUNT = 'filterinputcontainer-menucount';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_MENUS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_MENUCOUNT],
        );
    }

    public function getFilterInputComponents(array $component): array
    {
        $menuFilterInputModules = [
            ...$this->getIDFilterInputComponents(),
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SEARCH],
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SLUGS],
        ];
        $paginationFilterInputModules = $this->getPaginationFilterInputComponents();
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUTCONTAINER_MENUS => [
                ...$menuFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_MENUCOUNT => $menuFilterInputModules,
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
