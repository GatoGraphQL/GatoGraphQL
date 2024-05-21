<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoPCMSSchema\CustomPosts\ComponentProcessors\CustomPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;

abstract class AbstractPageFilterInputContainerComponentProcessor extends CustomPostFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const COMPONENT_FILTERINPUTCONTAINER_PAGELISTLIST = 'filterinputcontainer-pagelist';
    public final const COMPONENT_FILTERINPUTCONTAINER_PAGELISTCOUNT = 'filterinputcontainer-pagecount';
    public final const COMPONENT_FILTERINPUTCONTAINER_ADMINPAGELISTLIST = 'filterinputcontainer-adminpagelist';
    public final const COMPONENT_FILTERINPUTCONTAINER_ADMINPAGELISTCOUNT = 'filterinputcontainer-adminpagecount';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUTCONTAINER_PAGELISTLIST,
            self::COMPONENT_FILTERINPUTCONTAINER_PAGELISTCOUNT,
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINPAGELISTLIST,
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINPAGELISTCOUNT,
        );
    }

    /**
     * @return Component[]
     */
    public function getFilterInputComponents(Component $component): array
    {
        // Get the original config from above
        $targetComponent = match ($component->name) {
            self::COMPONENT_FILTERINPUTCONTAINER_PAGELISTLIST => new Component(parent::class, parent::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTLISTLIST),
            self::COMPONENT_FILTERINPUTCONTAINER_PAGELISTCOUNT => new Component(parent::class, parent::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTLISTCOUNT),
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINPAGELISTLIST => new Component(parent::class, parent::COMPONENT_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTLIST),
            self::COMPONENT_FILTERINPUTCONTAINER_ADMINPAGELISTCOUNT => new Component(parent::class, parent::COMPONENT_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTCOUNT),
            default => null,
        };
        if ($targetComponent === null) {
            return [];
        }
        $filterInputComponents = parent::getFilterInputComponents($targetComponent);
        // Add the parentIDs and parentID filterInputs
        $filterInputComponents[] = new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_PARENT_IDS);
        $filterInputComponents[] = new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_PARENT_ID);
        $filterInputComponents[] = new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_EXCLUDE_PARENT_IDS);
        return $filterInputComponents;
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
