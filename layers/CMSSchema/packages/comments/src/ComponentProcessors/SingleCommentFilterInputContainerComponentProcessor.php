<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\ComponentProcessors;

use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPCMSSchema\Comments\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\AbstractFilterInputContainerComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;

class SingleCommentFilterInputContainerComponentProcessor extends AbstractFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const COMPONENT_FILTERINPUTCONTAINER_COMMENT_STATUS = 'filterinputcontainer-comment-status';
    public final const COMPONENT_FILTERINPUTCONTAINER_COMMENT_BY_ID_STATUS = 'filterinputcontainer-comment-by-id-status';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_COMMENT_STATUS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_COMMENT_BY_ID_STATUS],
        );
    }

    public function getFilterInputComponents(array $component): array
    {
        return match ((string)$component[1]) {
            self::COMPONENT_FILTERINPUTCONTAINER_COMMENT_STATUS => [
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_COMMENT_STATUS],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_COMMENT_BY_ID_STATUS => [
                [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_ID],
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_COMMENT_STATUS],
            ],
            default => [],
        };
    }

    public function getFieldFilterInputTypeModifiers(array $component, string $fieldArgName): int
    {
        $fieldFilterInputTypeModifiers = parent::getFieldFilterInputTypeModifiers($component, $fieldArgName);
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUTCONTAINER_COMMENT_BY_ID_STATUS:
                $idFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputComponentProcessor::class,
                    CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_ID
                ]);
                if ($fieldArgName === $idFilterInputName) {
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
