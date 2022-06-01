<?php

declare(strict_types=1);

namespace PoPCMSSchema\GenericCustomPosts\ComponentProcessors;

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

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTLIST],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTCOUNT],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTLIST],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTCOUNT],
        );
    }

    public function getFilterInputComponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $genericCustomPostFilterInputComponents = [
            ...$this->getIDFilterInputComponents(),
            new \PoP\ComponentModel\Component\Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SEARCH),
            new \PoP\ComponentModel\Component\Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_GENERICCUSTOMPOSTTYPES),
        ];
        $adminGenericCustomPostFilterInputComponents = [
            new \PoP\ComponentModel\Component\Component(CustomPostFilterInputComponentProcessor::class, CustomPostFilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS),
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
