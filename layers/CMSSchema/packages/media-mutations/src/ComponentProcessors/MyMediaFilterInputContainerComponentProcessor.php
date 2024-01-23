<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoPCMSSchema\Media\ComponentProcessors\MediaFilterInputContainerComponentProcessor;

class MyMediaFilterInputContainerComponentProcessor extends MediaFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const COMPONENT_FILTERINPUTCONTAINER_MYMEDIAITEMS = 'filterinputcontainer-mymediaitems';
    public final const COMPONENT_FILTERINPUTCONTAINER_MYMEDIAITEMCOUNT = 'filterinputcontainer-mymediaitem-count';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUTCONTAINER_MYMEDIAITEMS,
            self::COMPONENT_FILTERINPUTCONTAINER_MYMEDIAITEMCOUNT,
        );
    }

    /**
     * @return Component[]
     */
    public function getFilterInputComponents(Component $component): array
    {
        // Get the original config from above
        $targetComponent = match ($component->name) {
            self::COMPONENT_FILTERINPUTCONTAINER_MYMEDIAITEMS => new Component(parent::class, parent::COMPONENT_FILTERINPUTCONTAINER_MEDIAITEMS),
            self::COMPONENT_FILTERINPUTCONTAINER_MYMEDIAITEMCOUNT => new Component(parent::class, parent::COMPONENT_FILTERINPUTCONTAINER_MEDIAITEMCOUNT),
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
