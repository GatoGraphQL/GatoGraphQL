<?php

declare(strict_types=1);

namespace PoPCMSSchema\GenericCustomPosts\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor as CustomPostFilterInputComponentProcessor;
use PoPCMSSchema\GenericCustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\AbstractFilterInputContainerComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;

class CommonCustomPostFilterInputContainerComponentProcessor extends AbstractFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_GENERICTYPE = 'filterinputcontainer-custompost-by-generictype';
    public final const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_STATUS_GENERICTYPE = 'filterinputcontainer-custompost-by-status-generictype';
    public final const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_GENERICTYPE = 'filterinputcontainer-custompost-by-id-generictype';
    public final const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS_GENERICTYPE = 'filterinputcontainer-custompost-by-id-status-generictype';
    public final const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_GENERICTYPE = 'filterinputcontainer-custompost-by-slug-generictype';
    public final const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS_GENERICTYPE = 'filterinputcontainer-custompost-by-slug-status-generictype';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_GENERICTYPE],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_STATUS_GENERICTYPE],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_GENERICTYPE],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS_GENERICTYPE],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_GENERICTYPE],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS_GENERICTYPE],
        );
    }

    public function getFilterInputComponents(\PoP\ComponentModel\Component\Component $component): array
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_GENERICTYPE => [
                new \PoP\ComponentModel\Component\Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_GENERICCUSTOMPOSTTYPES),
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_STATUS_GENERICTYPE => [
                new \PoP\ComponentModel\Component\Component(CustomPostFilterInputComponentProcessor::class, CustomPostFilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS),
                new \PoP\ComponentModel\Component\Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_GENERICCUSTOMPOSTTYPES),
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_GENERICTYPE => [
                new \PoP\ComponentModel\Component\Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_ID),
                new \PoP\ComponentModel\Component\Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_GENERICCUSTOMPOSTTYPES),
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS_GENERICTYPE => [
                new \PoP\ComponentModel\Component\Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_ID),
                new \PoP\ComponentModel\Component\Component(CustomPostFilterInputComponentProcessor::class, CustomPostFilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS),
                new \PoP\ComponentModel\Component\Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_GENERICCUSTOMPOSTTYPES),
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_GENERICTYPE => [
                new \PoP\ComponentModel\Component\Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SLUG),
                new \PoP\ComponentModel\Component\Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_GENERICCUSTOMPOSTTYPES),
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS_GENERICTYPE => [
                new \PoP\ComponentModel\Component\Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SLUG),
                new \PoP\ComponentModel\Component\Component(CustomPostFilterInputComponentProcessor::class, CustomPostFilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS),
                new \PoP\ComponentModel\Component\Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_GENERICCUSTOMPOSTTYPES),
            ],
            default => [],
        };
    }

    public function getFieldFilterInputTypeModifiers(\PoP\ComponentModel\Component\Component $component, string $fieldArgName): int
    {
        $fieldFilterInputTypeModifiers = parent::getFieldFilterInputTypeModifiers($component, $fieldArgName);
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_GENERICTYPE:
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS_GENERICTYPE:
                $idFilterInputName = FilterInputHelper::getFilterInputName(new Component(
                    CommonFilterInputComponentProcessor::class,
                    CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_ID
                ));
                if ($fieldArgName === $idFilterInputName) {
                    return $fieldFilterInputTypeModifiers | SchemaTypeModifiers::MANDATORY;
                }
                break;
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_GENERICTYPE:
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS_GENERICTYPE:
                $slugFilterInputName = FilterInputHelper::getFilterInputName(new Component(
                    CommonFilterInputComponentProcessor::class,
                    CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SLUG
                ));
                if ($fieldArgName === $slugFilterInputName) {
                    return $fieldFilterInputTypeModifiers | SchemaTypeModifiers::MANDATORY;
                }
                break;
        }
        return $fieldFilterInputTypeModifiers;
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
