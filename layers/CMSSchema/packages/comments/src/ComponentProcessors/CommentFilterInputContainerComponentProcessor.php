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
        $responseFilterInputModules = [
            ...$this->getIDFilterInputComponents(),
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SEARCH],
            [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_COMMENT_TYPES],
        ];
        $customPostCommentFilterInputModules = [
            ...$responseFilterInputModules,
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_PARENT_ID],
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_PARENT_IDS],
            [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_EXCLUDE_PARENT_IDS],
        ];
        $rootCommentFilterInputModules = [
            ...$customPostCommentFilterInputModules,
            [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOST_ID],
            [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS],
            [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS],
            [CustomPostFilterInputComponentProcessor::class, CustomPostFilterInputComponentProcessor::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES],
        ];
        $adminCommentFilterInputModules = [
            [FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_COMMENT_STATUS],
        ];
        $paginationFilterInputModules = $this->getPaginationFilterInputComponents();
        return match ((string)$component[1]) {
            self::COMPONENT_FILTERINPUTCONTAINER_RESPONSECOUNT => $responseFilterInputModules,
            self::COMPONENT_FILTERINPUTCONTAINER_RESPONSES => [
                ...$responseFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTCOUNT => $customPostCommentFilterInputModules,
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTS => [
                ...$customPostCommentFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_COMMENTCOUNT => $rootCommentFilterInputModules,
            self::COMPONENT_FILTERINPUTCONTAINER_COMMENTS => [
                ...$rootCommentFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINRESPONSECOUNT => [
                ...$responseFilterInputModules,
                ...$adminCommentFilterInputModules,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINRESPONSES => [
                ...$responseFilterInputModules,
                ...$paginationFilterInputModules,
                ...$adminCommentFilterInputModules,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTCOUNT => [
                ...$customPostCommentFilterInputModules,
                ...$adminCommentFilterInputModules,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTS => [
                ...$customPostCommentFilterInputModules,
                ...$paginationFilterInputModules,
                ...$adminCommentFilterInputModules,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINCOMMENTCOUNT => [
                ...$rootCommentFilterInputModules,
                ...$adminCommentFilterInputModules,
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINCOMMENTS => [
                ...$rootCommentFilterInputModules,
                ...$paginationFilterInputModules,
                ...$adminCommentFilterInputModules,
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
