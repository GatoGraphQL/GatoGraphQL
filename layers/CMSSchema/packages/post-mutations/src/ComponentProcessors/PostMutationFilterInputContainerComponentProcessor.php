<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor as CustomPostFilterInputComponentProcessor;
use PoPCMSSchema\Posts\ComponentProcessors\AbstractPostFilterInputContainerComponentProcessor;

class PostMutationFilterInputContainerComponentProcessor extends AbstractPostFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const COMPONENT_FILTERINPUTCONTAINER_MYPOSTS = 'filterinputcontainer-myposts';
    public final const COMPONENT_FILTERINPUTCONTAINER_MYPOSTCOUNT = 'filterinputcontainer-mypostcount';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUTCONTAINER_MYPOSTS,
            self::COMPONENT_FILTERINPUTCONTAINER_MYPOSTCOUNT,
        );
    }

    /**
     * Retrieve the same elements as for Posts, and add the "status" filter
     *
     * @return Component[]
     */
    public function getFilterInputComponents(Component $component): array
    {
        $targetModule = match ($component->name) {
            self::COMPONENT_FILTERINPUTCONTAINER_MYPOSTS => self::COMPONENT_FILTERINPUTCONTAINER_POSTS,
            self::COMPONENT_FILTERINPUTCONTAINER_MYPOSTCOUNT => self::COMPONENT_FILTERINPUTCONTAINER_POSTCOUNT,
            default => null,
        };
        $filterInputComponents = parent::getFilterInputComponents($targetModule);
        $filterInputComponents[] = new Component(CustomPostFilterInputComponentProcessor::class, CustomPostFilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS);
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
