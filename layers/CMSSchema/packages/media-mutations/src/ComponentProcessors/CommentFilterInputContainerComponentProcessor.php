<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoPCMSSchema\Comments\ComponentProcessors\CommentFilterInputContainerComponentProcessor as UpstreamCommentFilterInputContainerComponentProcessor;

class CommentFilterInputContainerComponentProcessor extends UpstreamCommentFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const COMPONENT_FILTERINPUTCONTAINER_MYCOMMENTS = 'filterinputcontainer-mycomments';
    public final const COMPONENT_FILTERINPUTCONTAINER_MYCOMMENTCOUNT = 'filterinputcontainer-mycommentcount';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUTCONTAINER_MYCOMMENTS,
            self::COMPONENT_FILTERINPUTCONTAINER_MYCOMMENTCOUNT,
        );
    }

    /**
     * @return Component[]
     */
    public function getFilterInputComponents(Component $component): array
    {
        // Get the original config from above
        $targetComponent = match ($component->name) {
            self::COMPONENT_FILTERINPUTCONTAINER_MYCOMMENTS => new Component(parent::class, parent::COMPONENT_FILTERINPUTCONTAINER_ADMINCOMMENTS),
            self::COMPONENT_FILTERINPUTCONTAINER_MYCOMMENTCOUNT => new Component(parent::class, parent::COMPONENT_FILTERINPUTCONTAINER_ADMINCOMMENTCOUNT),
            default => null,
        };
        return parent::getFilterInputComponents($targetComponent ?? $component);
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
