<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\ComponentProcessors;

use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;

class CustomPostFilterInputContainerComponentProcessor extends AbstractCustomPostFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const COMPONENT_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTLIST = 'filterinputcontainer-unioncustompostlist';
    public final const COMPONENT_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTCOUNT = 'filterinputcontainer-unioncustompostcount';
    public final const COMPONENT_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTLIST = 'filterinputcontainer-adminunioncustompostlist';
    public final const COMPONENT_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTCOUNT = 'filterinputcontainer-adminunioncustompostcount';
    public final const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTLISTLIST = 'filterinputcontainer-custompostlist';
    public final const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTLISTCOUNT = 'filterinputcontainer-custompostcount';
    public final const COMPONENT_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTLIST = 'filterinputcontainer-admincustompostlist';
    public final const COMPONENT_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTCOUNT = 'filterinputcontainer-admincustompostcount';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTLIST],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTCOUNT],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTLIST],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTCOUNT],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTLISTLIST],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTLISTCOUNT],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTLIST],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTCOUNT],
        );
    }

    public function getFilterInputComponents(array $component): array
    {
        $customPostFilterInputModules = [
            ...$this->getIDFilterInputComponents(),
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SEARCH],
        ];
        $unionCustomPostFilterInputModules = [
            [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES],
        ];
        $adminCustomPostFilterInputModules = [
            [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS],
        ];
        $paginationFilterInputModules = $this->getPaginationFilterInputComponents();
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTLIST => [
                ...$customPostFilterInputModules,
                ...$unionCustomPostFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTLIST => [
                ...$customPostFilterInputModules,
                ...$unionCustomPostFilterInputModules,
                ...$adminCustomPostFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTLISTLIST => [
                ...$customPostFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTLIST => [
                ...$customPostFilterInputModules,
                ...$adminCustomPostFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTCOUNT => [
                ...$customPostFilterInputModules,
                ...$unionCustomPostFilterInputModules,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTCOUNT => [
                ...$customPostFilterInputModules,
                ...$adminCustomPostFilterInputModules,
                ...$unionCustomPostFilterInputModules,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTLISTCOUNT => $customPostFilterInputModules,
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTCOUNT => [
                ...$customPostFilterInputModules,
                ...$adminCustomPostFilterInputModules,
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
