<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\ComponentProcessors;

use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\AbstractFilterInputContainerComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;

class CommonCustomPostFilterInputContainerComponentProcessor extends AbstractFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTSTATUS = 'filterinputcontainer-custompoststatus';
    public final const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_UNIONTYPE = 'filterinputcontainer-custompost-by-uniontype';
    public final const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_STATUS_UNIONTYPE = 'filterinputcontainer-custompost-by-status-uniontype';
    public final const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS = 'filterinputcontainer-custompost-by-id-status';
    public final const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_UNIONTYPE = 'filterinputcontainer-custompost-by-id-uniontype';
    public final const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS_UNIONTYPE = 'filterinputcontainer-custompost-by-id-status-uniontype';
    public final const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS = 'filterinputcontainer-custompost-by-slug-status';
    public final const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_UNIONTYPE = 'filterinputcontainer-custompost-by-slug-uniontype';
    public final const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS_UNIONTYPE = 'filterinputcontainer-custompost-by-slug-status-uniontype';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTSTATUS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_UNIONTYPE],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_STATUS_UNIONTYPE],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_UNIONTYPE],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS_UNIONTYPE],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_UNIONTYPE],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS_UNIONTYPE],
        );
    }

    public function getFilterInputComponents(array $component): array
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTSTATUS => [
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_UNIONTYPE => [
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_STATUS_UNIONTYPE => [
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS],
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS => [
                [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_ID],
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_UNIONTYPE => [
                [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_ID],
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS_UNIONTYPE => [
                [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_ID],
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS],
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS => [
                [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SLUG],
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_UNIONTYPE => [
                [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SLUG],
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS_UNIONTYPE => [
                [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SLUG],
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS],
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES],
            ],
            default => [],
        };
    }

    public function getFieldFilterInputTypeModifiers(array $component, string $fieldArgName): int
    {
        $fieldFilterInputTypeModifiers = parent::getFieldFilterInputTypeModifiers($component, $fieldArgName);
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS:
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_UNIONTYPE:
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS_UNIONTYPE:
                $idFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputComponentProcessor::class,
                    CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_ID
                ]);
                if ($fieldArgName === $idFilterInputName) {
                    return $fieldFilterInputTypeModifiers | SchemaTypeModifiers::MANDATORY;
                }
                break;
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS:
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_UNIONTYPE:
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS_UNIONTYPE:
                $slugFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputComponentProcessor::class,
                    CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SLUG
                ]);
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
