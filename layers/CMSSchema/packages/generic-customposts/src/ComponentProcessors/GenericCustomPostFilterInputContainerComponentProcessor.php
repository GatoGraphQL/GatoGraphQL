<?php

declare(strict_types=1);

namespace PoPCMSSchema\GenericCustomPosts\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoPCMSSchema\CustomPosts\ComponentProcessors\AbstractCustomPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor as CustomPostFilterInputComponentProcessor;
use PoPCMSSchema\GenericCustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;

class GenericCustomPostFilterInputContainerComponentProcessor extends AbstractCustomPostFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const COMPONENT_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTLIST = 'filterinputcontainer-genericcustompostlist';
    public final const COMPONENT_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTCOUNT = 'filterinputcontainer-genericcustompostcount';
    public final const COMPONENT_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTLIST = 'filterinputcontainer-admingenericcustompostlist';
    public final const COMPONENT_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTCOUNT = 'filterinputcontainer-admingenericcustompostcount';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTLIST,
            self::COMPONENT_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTCOUNT,
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTLIST,
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTCOUNT,
        );
    }

    /**
     * @return Component[]
     */
    public function getFilterInputComponents(Component $component): array
    {
        $genericCustomPostFilterInputComponents = [
            ...$this->getIDFilterInputComponents(),
            new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SEARCH),
            new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_GENERICCUSTOMPOSTTYPES),
        ];
        $adminGenericCustomPostFilterInputComponents = [
            new Component(CustomPostFilterInputComponentProcessor::class, CustomPostFilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS),
        ];
        $paginationFilterInputComponents = $this->getPaginationFilterInputComponents();
        return match ($component->name) {
            self::COMPONENT_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTLIST=> [
                ...$genericCustomPostFilterInputComponents,
                ...$paginationFilterInputComponents,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTLIST => [
                    ...$genericCustomPostFilterInputComponents,
                    ...$adminGenericCustomPostFilterInputComponents,
                    ...$paginationFilterInputComponents,
                ],
            self::COMPONENT_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTCOUNT => $genericCustomPostFilterInputComponents,
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTCOUNT => [
                ...$genericCustomPostFilterInputComponents,
                ...$adminGenericCustomPostFilterInputComponents,
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
