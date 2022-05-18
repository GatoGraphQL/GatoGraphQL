<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\ComponentProcessors;

use PoPCMSSchema\Comments\ComponentProcessors\CommentFilterInputContainerComponentProcessor as UpstreamCommentFilterInputContainerComponentProcessor;

class CommentFilterInputContainerComponentProcessor extends UpstreamCommentFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const COMPONENT_FILTERINPUTCONTAINER_MYCOMMENTS = 'filterinputcontainer-mycomments';
    public final const COMPONENT_FILTERINPUTCONTAINER_MYCOMMENTCOUNT = 'filterinputcontainer-mycommentcount';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_MYCOMMENTS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_MYCOMMENTCOUNT],
        );
    }

    public function getFilterInputComponents(array $component): array
    {
        // Get the original config from above
        $targetModule = match ($component[1]) {
            self::COMPONENT_FILTERINPUTCONTAINER_MYCOMMENTS => [parent::class, parent::COMPONENT_FILTERINPUTCONTAINER_ADMINCOMMENTS],
            self::COMPONENT_FILTERINPUTCONTAINER_MYCOMMENTCOUNT => [parent::class, parent::COMPONENT_FILTERINPUTCONTAINER_ADMINCOMMENTCOUNT],
            default => null,
        };
        return parent::getFilterInputComponents($targetModule ?? $component);
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
