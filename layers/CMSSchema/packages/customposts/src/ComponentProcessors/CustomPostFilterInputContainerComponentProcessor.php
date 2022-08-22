<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
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

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTLIST,
            self::COMPONENT_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTCOUNT,
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTLIST,
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTCOUNT,
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTLISTLIST,
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTLISTCOUNT,
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTLIST,
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTCOUNT,
        );
    }

    /**
     * @return Component[]
     */
    public function getFilterInputComponents(Component $component): array
    {
        $customPostFilterInputComponents = [
            ...$this->getIDFilterInputComponents(),
            new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SEARCH),
        ];
        $unionCustomPostFilterInputComponents = [
            new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES),
        ];
        $adminCustomPostFilterInputComponents = [
            new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS),
        ];
        $paginationFilterInputComponents = $this->getPaginationFilterInputComponents();
        return match ($component->name) {
            self::COMPONENT_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTLIST => [
                ...$customPostFilterInputComponents,
                ...$unionCustomPostFilterInputComponents,
                ...$paginationFilterInputComponents,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTLIST => [
                ...$customPostFilterInputComponents,
                ...$unionCustomPostFilterInputComponents,
                ...$adminCustomPostFilterInputComponents,
                ...$paginationFilterInputComponents,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTLISTLIST => [
                ...$customPostFilterInputComponents,
                ...$paginationFilterInputComponents,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTLIST => [
                ...$customPostFilterInputComponents,
                ...$adminCustomPostFilterInputComponents,
                ...$paginationFilterInputComponents,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTCOUNT => [
                ...$customPostFilterInputComponents,
                ...$unionCustomPostFilterInputComponents,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTCOUNT => [
                ...$customPostFilterInputComponents,
                ...$adminCustomPostFilterInputComponents,
                ...$unionCustomPostFilterInputComponents,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTLISTCOUNT => $customPostFilterInputComponents,
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTCOUNT => [
                ...$customPostFilterInputComponents,
                ...$adminCustomPostFilterInputComponents,
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
