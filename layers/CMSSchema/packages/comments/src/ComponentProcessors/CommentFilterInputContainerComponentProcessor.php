<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\ComponentProcessors;

use PoPCMSSchema\Comments\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor as CustomPostFilterInputComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\AbstractFilterInputContainerComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;

class CommentFilterInputContainerComponentProcessor extends AbstractFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const COMPONENT_FILTERINPUTCONTAINER_COMMENTS = 'filterinputcontainer-comments';
    public final const COMPONENT_FILTERINPUTCONTAINER_COMMENTCOUNT = 'filterinputcontainer-commentcount';
    public final const COMPONENT_FILTERINPUTCONTAINER_RESPONSES = 'filterinputcontainer-responses';
    public final const COMPONENT_FILTERINPUTCONTAINER_RESPONSECOUNT = 'filterinputcontainer-responsecount';
    public final const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTS = 'filterinputcontainer-custompost-comments';
    public final const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTCOUNT = 'filterinputcontainer-custompost-commentcount';
    public final const COMPONENT_FILTERINPUTCONTAINER_ADMINCOMMENTS = 'filterinputcontainer-admincomments';
    public final const COMPONENT_FILTERINPUTCONTAINER_ADMINCOMMENTCOUNT = 'filterinputcontainer-admincommentcount';
    public final const COMPONENT_FILTERINPUTCONTAINER_ADMINRESPONSES = 'filterinputcontainer-adminresponses';
    public final const COMPONENT_FILTERINPUTCONTAINER_ADMINRESPONSECOUNT = 'filterinputcontainer-adminresponsecount';
    public final const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTS = 'filterinputcontainer-custompost-admincomments';
    public final const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTCOUNT = 'filterinputcontainer-custompost-admincommentcount';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_COMMENTS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_COMMENTCOUNT],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_RESPONSES],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_RESPONSECOUNT],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTCOUNT],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_ADMINCOMMENTS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_ADMINCOMMENTCOUNT],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_ADMINRESPONSES],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_ADMINRESPONSECOUNT],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTCOUNT],
        );
    }

    public function getFilterInputComponents(array $component): array
    {
        $responseFilterInputComponents = [
            ...$this->getIDFilterInputComponents(),
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SEARCH],
            [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_COMMENT_TYPES],
        ];
        $customPostCommentFilterInputComponents = [
            ...$responseFilterInputComponents,
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_PARENT_ID],
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_PARENT_IDS],
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_EXCLUDE_PARENT_IDS],
        ];
        $rootCommentFilterInputComponents = [
            ...$customPostCommentFilterInputComponents,
            [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOST_ID],
            [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS],
            [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS],
            [CustomPostFilterInputComponentProcessor::class, CustomPostFilterInputComponentProcessor::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES],
        ];
        $adminCommentFilterInputComponents = [
            [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_COMMENT_STATUS],
        ];
        $paginationFilterInputComponents = $this->getPaginationFilterInputComponents();
        return match ((string)$component[1]) {
            self::COMPONENT_FILTERINPUTCONTAINER_RESPONSECOUNT => $responseFilterInputComponents,
            self::COMPONENT_FILTERINPUTCONTAINER_RESPONSES => [
                ...$responseFilterInputComponents,
                ...$paginationFilterInputComponents,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTCOUNT => $customPostCommentFilterInputComponents,
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTS => [
                ...$customPostCommentFilterInputComponents,
                ...$paginationFilterInputComponents,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_COMMENTCOUNT => $rootCommentFilterInputComponents,
            self::COMPONENT_FILTERINPUTCONTAINER_COMMENTS => [
                ...$rootCommentFilterInputComponents,
                ...$paginationFilterInputComponents,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINRESPONSECOUNT => [
                ...$responseFilterInputComponents,
                ...$adminCommentFilterInputComponents,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINRESPONSES => [
                ...$responseFilterInputComponents,
                ...$paginationFilterInputComponents,
                ...$adminCommentFilterInputComponents,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTCOUNT => [
                ...$customPostCommentFilterInputComponents,
                ...$adminCommentFilterInputComponents,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTS => [
                ...$customPostCommentFilterInputComponents,
                ...$paginationFilterInputComponents,
                ...$adminCommentFilterInputComponents,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINCOMMENTCOUNT => [
                ...$rootCommentFilterInputComponents,
                ...$adminCommentFilterInputComponents,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINCOMMENTS => [
                ...$rootCommentFilterInputComponents,
                ...$paginationFilterInputComponents,
                ...$adminCommentFilterInputComponents,
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
