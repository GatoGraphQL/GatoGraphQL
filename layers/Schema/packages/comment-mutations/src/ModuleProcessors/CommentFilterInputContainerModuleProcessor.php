<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\ModuleProcessors;

use PoPSchema\Comments\ModuleProcessors\CommentFilterInputContainerModuleProcessor as UpstreamCommentFilterInputContainerModuleProcessor;

class CommentFilterInputContainerModuleProcessor extends UpstreamCommentFilterInputContainerModuleProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public const MODULE_FILTERINPUTCONTAINER_MYCOMMENT = 'filterinputcontainer-mycomment';
    public const MODULE_FILTERINPUTCONTAINER_MYCOMMENTS = 'filterinputcontainer-mycomments';
    public const MODULE_FILTERINPUTCONTAINER_MYCOMMENTCOUNT = 'filterinputcontainer-mycommentcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MYCOMMENT],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MYCOMMENTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MYCOMMENTCOUNT],
        );
    }

    public function getFilterInputModules(array $module): array
    {
        // Get the original config from above
        $targetModule = match ($module[1]) {
            self::MODULE_FILTERINPUTCONTAINER_MYCOMMENT => [parent::class, parent::MODULE_FILTERINPUTCONTAINER_COMMENT_BY_ID_STATUS],
            self::MODULE_FILTERINPUTCONTAINER_MYCOMMENTS => [parent::class, parent::MODULE_FILTERINPUTCONTAINER_ADMINCOMMENTS],
            self::MODULE_FILTERINPUTCONTAINER_MYCOMMENTCOUNT => [parent::class, parent::MODULE_FILTERINPUTCONTAINER_ADMINCOMMENTCOUNT],
            default => null,
        };
        return parent::getFilterInputModules($targetModule);
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
