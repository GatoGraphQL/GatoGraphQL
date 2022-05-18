<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\ComponentProcessors;

use PoPCMSSchema\Comments\ComponentProcessors\CommentFilterInputContainerComponentProcessor as UpstreamCommentFilterInputContainerComponentProcessor;

class CommentFilterInputContainerComponentProcessor extends UpstreamCommentFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const MODULE_FILTERINPUTCONTAINER_MYCOMMENTS = 'filterinputcontainer-mycomments';
    public final const MODULE_FILTERINPUTCONTAINER_MYCOMMENTCOUNT = 'filterinputcontainer-mycommentcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MYCOMMENTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MYCOMMENTCOUNT],
        );
    }

    public function getFilterInputComponentVariations(array $componentVariation): array
    {
        // Get the original config from above
        $targetModule = match ($componentVariation[1]) {
            self::MODULE_FILTERINPUTCONTAINER_MYCOMMENTS => [parent::class, parent::MODULE_FILTERINPUTCONTAINER_ADMINCOMMENTS],
            self::MODULE_FILTERINPUTCONTAINER_MYCOMMENTCOUNT => [parent::class, parent::MODULE_FILTERINPUTCONTAINER_ADMINCOMMENTCOUNT],
            default => null,
        };
        return parent::getFilterInputComponentVariations($targetModule ?? $componentVariation);
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
