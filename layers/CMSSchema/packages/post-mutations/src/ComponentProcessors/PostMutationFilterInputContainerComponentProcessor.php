<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\ComponentProcessors;

use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor as CustomPostFilterInputComponentProcessor;
use PoPCMSSchema\Posts\ComponentProcessors\AbstractPostFilterInputContainerComponentProcessor;

class PostMutationFilterInputContainerComponentProcessor extends AbstractPostFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const COMPONENT_FILTERINPUTCONTAINER_MYPOSTS = 'filterinputcontainer-myposts';
    public final const COMPONENT_FILTERINPUTCONTAINER_MYPOSTCOUNT = 'filterinputcontainer-mypostcount';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_MYPOSTS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_MYPOSTCOUNT],
        );
    }

    /**
     * Retrieve the same elements as for Posts, and add the "status" filter
     */
    public function getFilterInputComponents(array $component): array
    {
        $targetModule = match ($component[1]) {
            self::COMPONENT_FILTERINPUTCONTAINER_MYPOSTS => [self::class, self::COMPONENT_FILTERINPUTCONTAINER_POSTS],
            self::COMPONENT_FILTERINPUTCONTAINER_MYPOSTCOUNT => [self::class, self::COMPONENT_FILTERINPUTCONTAINER_POSTCOUNT],
            default => null,
        };
        $filterInputComponents = parent::getFilterInputComponents($targetModule);
        $filterInputComponents[] = new \PoP\ComponentModel\Component\Component(CustomPostFilterInputComponentProcessor::class, CustomPostFilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS);
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
