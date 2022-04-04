<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\ModuleProcessors;

use PoPCMSSchema\Comments\ModuleProcessors\FormInputs\FilterInputModuleProcessor;
use PoPCMSSchema\CustomPosts\ModuleProcessors\FormInputs\FilterInputModuleProcessor as CustomPostFilterInputModuleProcessor;
use PoPCMSSchema\SchemaCommons\ModuleProcessors\AbstractFilterInputContainerModuleProcessor;
use PoPCMSSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;

class CommentFilterInputContainerModuleProcessor extends AbstractFilterInputContainerModuleProcessor
{
    public final const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const MODULE_FILTERINPUTCONTAINER_COMMENTS = 'filterinputcontainer-comments';
    public final const MODULE_FILTERINPUTCONTAINER_COMMENTCOUNT = 'filterinputcontainer-commentcount';
    public final const MODULE_FILTERINPUTCONTAINER_RESPONSES = 'filterinputcontainer-responses';
    public final const MODULE_FILTERINPUTCONTAINER_RESPONSECOUNT = 'filterinputcontainer-responsecount';
    public final const MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTS = 'filterinputcontainer-custompost-comments';
    public final const MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTCOUNT = 'filterinputcontainer-custompost-commentcount';
    public final const MODULE_FILTERINPUTCONTAINER_ADMINCOMMENTS = 'filterinputcontainer-admincomments';
    public final const MODULE_FILTERINPUTCONTAINER_ADMINCOMMENTCOUNT = 'filterinputcontainer-admincommentcount';
    public final const MODULE_FILTERINPUTCONTAINER_ADMINRESPONSES = 'filterinputcontainer-adminresponses';
    public final const MODULE_FILTERINPUTCONTAINER_ADMINRESPONSECOUNT = 'filterinputcontainer-adminresponsecount';
    public final const MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTS = 'filterinputcontainer-custompost-admincomments';
    public final const MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTCOUNT = 'filterinputcontainer-custompost-admincommentcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_COMMENTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_COMMENTCOUNT],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_RESPONSES],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_RESPONSECOUNT],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTCOUNT],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ADMINCOMMENTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ADMINCOMMENTCOUNT],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ADMINRESPONSES],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ADMINRESPONSECOUNT],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTCOUNT],
        );
    }

    public function getFilterInputModules(array $module): array
    {
        $responseFilterInputModules = [
            ...$this->getIDFilterInputModules(),
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SEARCH],
            [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_COMMENT_TYPES],
        ];
        $customPostCommentFilterInputModules = [
            ...$responseFilterInputModules,
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_PARENT_ID],
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_PARENT_IDS],
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_EXCLUDE_PARENT_IDS],
        ];
        $rootCommentFilterInputModules = [
            ...$customPostCommentFilterInputModules,
            [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_CUSTOMPOST_ID],
            [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_CUSTOMPOST_IDS],
            [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS],
            [CustomPostFilterInputModuleProcessor::class, CustomPostFilterInputModuleProcessor::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES],
        ];
        $adminCommentFilterInputModules = [
            [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_COMMENT_STATUS],
        ];
        $paginationFilterInputModules = $this->getPaginationFilterInputModules();
        return match ((string)$module[1]) {
            self::MODULE_FILTERINPUTCONTAINER_RESPONSECOUNT => $responseFilterInputModules,
            self::MODULE_FILTERINPUTCONTAINER_RESPONSES => [
                ...$responseFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTCOUNT => $customPostCommentFilterInputModules,
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTS => [
                ...$customPostCommentFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_COMMENTCOUNT => $rootCommentFilterInputModules,
            self::MODULE_FILTERINPUTCONTAINER_COMMENTS => [
                ...$rootCommentFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_ADMINRESPONSECOUNT => [
                ...$responseFilterInputModules,
                ...$adminCommentFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_ADMINRESPONSES => [
                ...$responseFilterInputModules,
                ...$paginationFilterInputModules,
                ...$adminCommentFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTCOUNT => [
                ...$customPostCommentFilterInputModules,
                ...$adminCommentFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTS => [
                ...$customPostCommentFilterInputModules,
                ...$paginationFilterInputModules,
                ...$adminCommentFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_ADMINCOMMENTCOUNT => [
                ...$rootCommentFilterInputModules,
                ...$adminCommentFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_ADMINCOMMENTS => [
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
