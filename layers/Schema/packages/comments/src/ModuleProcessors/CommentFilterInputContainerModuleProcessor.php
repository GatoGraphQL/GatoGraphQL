<?php

declare(strict_types=1);

namespace PoPSchema\Comments\ModuleProcessors;

use PoPSchema\Comments\ModuleProcessors\FormInputs\FilterInputModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\AbstractFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterMultipleInputModuleProcessor;

class CommentFilterInputContainerModuleProcessor extends AbstractFilterInputContainerModuleProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public const MODULE_FILTERINPUTCONTAINER_COMMENTS = 'filterinputcontainer-comments';
    public const MODULE_FILTERINPUTCONTAINER_COMMENTCOUNT = 'filterinputcontainer-commentcount';
    public const MODULE_FILTERINPUTCONTAINER_RESPONSES = 'filterinputcontainer-responses';
    public const MODULE_FILTERINPUTCONTAINER_RESPONSECOUNT = 'filterinputcontainer-responsecount';
    public const MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTS = 'filterinputcontainer-custompost-comments';
    public const MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTCOUNT = 'filterinputcontainer-custompost-commentcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_COMMENTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_COMMENTCOUNT],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_RESPONSES],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_RESPONSECOUNT],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTCOUNT],
        );
    }

    public function getFilterInputModules(array $module): array
    {
        $responseFilterInputModules = [
            ...$this->getIDFilterInputModules(),
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SEARCH],
            [CommonFilterMultipleInputModuleProcessor::class, CommonFilterMultipleInputModuleProcessor::MODULE_FILTERINPUT_DATES],
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
        ];
        return match ($module[1]) {
            self::MODULE_FILTERINPUTCONTAINER_RESPONSECOUNT => $responseFilterInputModules,
            self::MODULE_FILTERINPUTCONTAINER_RESPONSES => [
                ...$responseFilterInputModules,
                ...$this->getPaginationFilterInputModules(),
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTCOUNT => $customPostCommentFilterInputModules,
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTS => [
                ...$customPostCommentFilterInputModules,
                ...$this->getPaginationFilterInputModules(),
            ],
            self::MODULE_FILTERINPUTCONTAINER_COMMENTCOUNT => $rootCommentFilterInputModules,
            self::MODULE_FILTERINPUTCONTAINER_COMMENTS => [
                ...$rootCommentFilterInputModules,
                ...$this->getPaginationFilterInputModules(),
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
